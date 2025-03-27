import { useEffect, useState, useRef, useCallback } from "react";
import "./style.css";
import api from "../../services/api";
import { Toaster, toast } from "sonner";
import {
  Button,
  Table,
  Modal,
  InputGroup,
  Pagination,
  Form,
} from "react-bootstrap";
import { FaTrash, FaPen, FaSearch } from "react-icons/fa";
import Header from "../../Components/Header/Header";
import Breadcrumb from "react-bootstrap/Breadcrumb";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Badge from 'react-bootstrap/Badge';

function Nivel() {
  const [niveis, setNiveis] = useState([]);
  const [meta, setMeta] = useState({});
  const [items, setItems] = useState([]);
  const [page, setPage] = useState(1);
  const [delShow, setDelShow] = useState(false);
  const [createShow, setCreateShow] = useState(false);
  const [deleteNivelId, setDeleteNivelId] = useState(null);
  const [updateShow, setUpdateShow] = useState(false);
  const [updateNivelId, setUpdateNivelId] = useState(null);
  const [btnLoading, setBtnLoading] = useState(false);
  const [orderBy, setOrderBy] = useState(null);
  const [orderDirection, setOrderDirection] = useState("asc");

  const inputNivel = useRef(null);
  const searchNivel = useRef(null);
  const inputUpdNivel = useRef(null);

  const handleDelClose = useCallback(() => setDelShow(false), []);
  const handleCreateClose = useCallback(() => setCreateShow(false), []);
  const handleDelShow = useCallback(() => setDelShow(true), []);
  const handleUpdateClose = useCallback(() => setUpdateShow(false), []);
  const handleUpdateShow = useCallback(() => setUpdateShow(true), []);
  const handleCreateShow = useCallback(() => setCreateShow(true), []);

  const handleClickDelete = useCallback(
    (id) => {
      setDeleteNivelId(id);
      handleDelShow();
    },
    [handleDelShow]
  );

  const handleDeleteItem = useCallback(() => {
    deleteNiveis(deleteNivelId);
  }, [deleteNivelId]);

  const handleClickUpdate = useCallback(
    (id) => {
      setUpdateNivelId(id);
      handleUpdateShow();
    },
    [handleUpdateShow]
  );

  const handleUpdateItem = useCallback(() => {
    updateNiveis(updateNivelId);
  }, [updateNivelId]);

  const getNiveis = useCallback(async () => {
    const p = document.getElementById("message");
    const search = searchNivel.current?.value || "";

    setMeta({});
    toast.info("Carregando...");

    let url = `/niveis?page=${page}&search=${search}`;
    if (orderBy) {
      url += `&order=${orderBy}&direction=${orderDirection}`;
    }

    try {
      const req = await api.get(url);
      setNiveis(req.data.data);
      setMeta(req.data.meta);
      p.textContent = "";
      paginate(req.data.meta);
    } catch (error) {
      if (error.response) {
        p.textContent = error.response.data.message;
        toast.error(error.response.data.message);
        setNiveis([]);
      } else {
        toast.error("Erro ao carregar os dados.");
      }
    }
  }, [page, orderBy, orderDirection]);

  const createNiveis = useCallback(async () => {
    setBtnLoading(true);
    toast.info("Cadastrando Níveis");

    try {
      await api.post("/niveis", { nivel: inputNivel.current?.value || "" });
      inputNivel.current.value = "";
      getNiveis();
      toast.success("Nível cadastrado com sucesso.");
    } catch (error) {
      if (error.response) {
        toast.error(error.response.data.message.nivel);
      } else {
        toast.error("Erro ao cadastrar nível.");
      }
    } finally {      
      setBtnLoading(false);
      handleCreateClose();
    }
  }, [getNiveis]);

  const updateNiveis = useCallback(
    async (id) => {
      const data = inputUpdNivel.current?.value || "";
      inputUpdNivel.current.value = "";
      handleUpdateClose();
      toast("Alterando Nível...");

      try {
        await api.put(`/niveis/${id}`, { nivel: data });
        getNiveis();
        toast.success("Nível atualizado com sucesso.");
      } catch (error) {
        toast.error(error.response.data.message);
      }
    },
    [getNiveis, handleUpdateClose]
  );

  const deleteNiveis = useCallback(
    async (id) => {
      handleDelClose();
      toast("Apagando Nível...");

      try {
        await api.delete(`/niveis/${id}`);
        getNiveis();
        toast.success("Nível apagado com sucesso.");
      } catch (error) {
        toast.error(error.response.data.message);
      }
    },
    [getNiveis, handleDelClose]
  );

  const paginate = useCallback(
    (metaData) => {
      let newItems = [];
      if (metaData && metaData.last_page) {
        for (let number = 1; number <= metaData.last_page; number++) {
          newItems.push(
            <Pagination.Item
              key={number}
              active={number === page}
              onClick={() => setPage(number)}
              disabled={metaData.last_page === 1 && number === 1}
            >
              {number}
            </Pagination.Item>
          );
        }
      }
      setItems(newItems);
    },
    [page]
  );

  const handleSort = useCallback(
    (field) => {
      if (orderBy === field) {
        setOrderDirection(orderDirection === "asc" ? "desc" : "asc");
      } else {
        setOrderBy(field);
        setOrderDirection("asc");
      }
      setPage(1);
    },
    [orderBy, orderDirection]
  );

  useEffect(() => {
    getNiveis();
  }, [page, getNiveis]);

  useEffect(() => {
    if (Object.keys(meta).length > 0) {
      paginate(meta);
    }
  }, [meta, page, paginate]);

  return (
    <>
      <Header />

      <div className="container-fluid">
        <Breadcrumb>
          <Breadcrumb.Item href="/">Home</Breadcrumb.Item>
          <Breadcrumb.Item active>Níveis</Breadcrumb.Item>
        </Breadcrumb>

        <Container>
          <Row>
            <Col className="text-center">
              <h3>Níveis</h3>
            </Col>
            <Col xs={6} className="justify-content-center">
              <InputGroup className="justify-content-center">
                <Form.Control placeholder="Pesquisar" ref={searchNivel} />
                <Button variant="outline-secondary" onClick={getNiveis}>
                  <FaSearch />
                </Button>
              </InputGroup>
            </Col>
            <Col className="text-center">
              <Button variant="success" onClick={handleCreateShow}> Cadastrar </Button>
            </Col>
          </Row>
        </Container>

        <Table striped hover>
          <thead>
            <tr>
              <th onClick={() => handleSort("id")} className="action">
                #
              </th>
              <th onClick={() => handleSort("nivel")} className="action">
                Nível
              </th>             
              <th></th>
            </tr>
          </thead>
          <tbody>
            {niveis.map((nivel) => (
              <tr key={nivel.id}>
                <td>{nivel.id}</td>
                <td>
                  <p>{nivel.nivel}
                  <Badge bg="primary" hidden={nivel.desenvolvedores_count === 0}>
                    {nivel.desenvolvedores_count}
                  </Badge>
                  </p>
                </td>
                <td>
                  <Button
                    variant="danger"
                    onClick={() => handleClickDelete(nivel.id)}
                  >
                    <FaTrash />
                  </Button>{" "}
                  <Button
                    variant="primary"
                    onClick={() => handleClickUpdate(nivel.id)}
                  >
                    <FaPen />
                  </Button>
                </td>
              </tr>
            ))}
          </tbody>
        </Table>
        <Container>
          <Row>
            <Col className="text-center">
            </Col>
            <Col className="justify-content-center" >
            <Pagination className="justify-content-center">{items}</Pagination>
            </Col>
            <Col className="text-center">
            </Col>
          </Row>
        </Container>
        <p id="message" />
        <Modal show={delShow} onHide={handleDelClose}>
          <Modal.Header closeButton>
            <Modal.Title>Excluir Nível</Modal.Title>
          </Modal.Header>
          <Modal.Body>Você gostaria de excluir?</Modal.Body>
          <Modal.Footer>
            <Button variant="danger" onClick={handleDeleteItem}>
              Sim
            </Button>
            <Button variant="primary" onClick={handleDelClose}>
              Não
            </Button>
          </Modal.Footer>
        </Modal>
        <Modal show={updateShow} onHide={handleUpdateClose}>
          <Modal.Header closeButton>
            <Modal.Title>Atualizar Nível</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Nível"
              ref={inputUpdNivel}
            />
          </Modal.Body>
          <Modal.Footer>
            <Button variant="warning" onClick={handleUpdateItem}>
              Atualizar
            </Button>
            <Button variant="primary" onClick={handleUpdateClose}>
              Cancelar
            </Button>
          </Modal.Footer>
        </Modal>

        <Modal show={createShow} onHide={handleDelClose}>
          <Modal.Header closeButton>
            <Modal.Title>Cadastrar Nível</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Nível"
              ref={inputNivel}
            />
           
          </Modal.Body>
          <Modal.Footer>
          <Button variant="primary" type="button" onClick={createNiveis} disabled={btnLoading}>
              {btnLoading ? "Carregando..." : "Cadastrar"}
            </Button>           
            <Button variant="danger" onClick={handleCreateClose}>
              Cancelar
            </Button>
          </Modal.Footer>
        </Modal>

        <div className="mensagem">
          <Toaster position="top-right" expand richColors />
        </div>
      </div>
    </>
  );
}

export default Nivel;
