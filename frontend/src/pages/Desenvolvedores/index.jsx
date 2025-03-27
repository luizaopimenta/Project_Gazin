import Header from "../../Components/Header/Header";
import "./style.css";
import { useEffect, useState, useRef, useCallback } from "react";
import { ptBR } from "date-fns/locale";
import api from "../../services/api";
import { Toaster, toast } from "sonner";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import {
  Button,
  Table,
  Modal,
  InputGroup,
  Pagination,
  Form,
} from "react-bootstrap";
import { FaTrash, FaPen, FaSearch } from "react-icons/fa";
import { registerLocale } from "react-datepicker";
registerLocale("ptBR", ptBR);
import Breadcrumb from "react-bootstrap/Breadcrumb";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import { isValid, parseISO } from "date-fns";


function Desenvolvedores() {
  const [niveis, setNiveis] = useState([]);
  const [desenvolvedores, setDesenvolvedores] = useState([]);
  const [meta, setMeta] = useState({});
  const [items, setItems] = useState([]);
  const [niveisSelect, setNiveisSelect] = useState([]);
  const [page, setPage] = useState(1);
  const [delShow, setDelShow] = useState(false);
  const [deleteDevId, setDeleteDevId] = useState(null);
  const [updateShow, setUpdateShow] = useState(false);
  const [updateDevId, setUpdateDevId] = useState(null);
  const [btnLoading, setBtnLoading] = useState(false);
  const [orderBy, setOrderBy] = useState(null);
  const [orderDirection, setOrderDirection] = useState("asc");
  const [startDate, setStartDate] = useState(null);
  const [startUDate, setStartUDate] = useState(null);
  const [slcDisabled, setSlcDisabled] = useState(true);
  const [createShow, setCreateShow] = useState(false);
  const [v_nome, setVNome] = useState("");
  const [v_sexo, setVSexo] = useState("");
  const [v_nivel_id, setVNivel_id] = useState("");
  const [v_hobby, setVHobby] = useState("");


  const searchNivel = useRef(null);

  const nome = useRef(null);
  const sexo = useRef(null);
  const nivel_id = useRef(null);
  const hobby = useRef(null);

  const unome = useRef(null);
  const usexo = useRef(null);
  const univel_id = useRef(null);
  const uhobby = useRef(null);
  const [v_data_nascimento, setVData_nascimento] = useState("");

  const handleDelClose = useCallback(() => setDelShow(false), []);
  const handleDelShow = useCallback(() => setDelShow(true), []);
  const handleUpdateClose = useCallback(() => setUpdateShow(false), []);
  const handleUpdateShow = useCallback(() => setUpdateShow(true), []);
  const handleCreateShow = useCallback(() => setCreateShow(true), []);
  const handleCreateClose = useCallback(() => setCreateShow(false), []);
  
  const handleClickDelete = useCallback(
    (id) => {
      setDeleteDevId(id);
      handleDelShow();
    },
    [handleDelShow]
  );

  const handleDeleteItem = useCallback(() => {
    deleteDesenvolvedores(deleteDevId);
  }, [deleteDevId]);

  useEffect(() => {
    if (updateShow) {
      if (unome.current) {
        unome.current.value = v_nome;
      }
      if (usexo.current) {
        usexo.current.value = v_sexo;
      }
      if (univel_id.current) {
        univel_id.current.value = v_nivel_id;
      }
      if (uhobby.current) {
        uhobby.current.value = v_hobby;
      }
      if (v_data_nascimento && isValid(parseISO(v_data_nascimento))) {       
        setStartUDate(parseISO(v_data_nascimento));
      } 
    }
  }, [updateShow, v_nome, v_sexo, v_nivel_id, v_hobby, v_data_nascimento]);

  const getAge = useCallback((dateString) => {
    if (dateString) {
      const parsedDate = new Date(dateString);
      setStartUDate(parsedDate); 
      setVData_nascimento(parsedDate.toISOString()); 
    }
  }, []);

  const handleClickUpdate = useCallback(
    (user) => {
      console.log(user);
      handleUpdateShow();
      setUpdateDevId(user.id);
      setVNome(user.nome);
      setVSexo(user.sexo);
      setVNivel_id(user.nivel.id);
      setVHobby(user.hobby);
      setVData_nascimento(user.data_nascimento);
    },
    [handleUpdateShow]
  );

  const handleUpdateItem = useCallback(() => {
    updateDesenvolvedores(updateDevId);
  }, [updateDevId]);

  const getNiveis = useCallback(async () => {
    const p = document.getElementById("message");
    toast.info("Carregando níveis...");
    let url = `/niveis?per_page=100`;
    try {
      const req = await api.get(url);
      setNiveis(req.data.data);
      p.textContent = "";
    } catch (error) {
      if (error.response) {
        p.textContent = error.response.data.message;
        toast.error(error.response.data.message);
        setNiveis([]);
      } else {
        toast.error("Erro ao carregar os dados.");
      }
    }
  }, []);

  const getDesenvolvedores = useCallback(async () => {
    const p = document.getElementById("message");
    const search = searchNivel.current?.value || "";
    setMeta({});
    toast.info("Carregando desenvolvedores...");

    let url = `/desenvolvedores?page=${page}&search=${search}`;
    if (orderBy) {
      url += `&order=${orderBy}&direction=${orderDirection}`;
    }

    try {
      const req = await api.get(url);
      setDesenvolvedores(req.data.data);
      setMeta(req.data.meta);
      p.textContent = "";
      paginate(req.data.meta);
    } catch (error) {
      if (error.response) {
        p.textContent = error.response.data.message;
        toast.error(error.response.data.message);
        setDesenvolvedores([]);
      } else {
        toast.error("Erro ao carregar os dados.");
      }
    }
  }, [page, orderBy, orderDirection]);

  const createDesenvolvedores = useCallback(async () => {
    setBtnLoading(true);
    toast.info("Cadastrando Desenvolvedores");
   
    try {
      await api.post("/desenvolvedores", {
        nome: nome.current?.value || "",
        sexo: sexo.current?.value || "",
        data_nascimento: startDate ? startDate.toISOString() : "",
        hobby: hobby.current?.value || "",
        nivel_id: nivel_id.current?.value || "",
      });

      getDesenvolvedores();
      toast.success("Desenvolvedor cadastrado com sucesso.");
    } catch (error) {
      if (error.response) {
        toast.error(
          error.response.data.message?.nivel_id ||
            "" + " " + error.response.data.message?.hobby ||
            "" + " " + error.response.data.message?.nome ||
            "" + " " + error.response.data.message?.message?.data_nascimento ||
            "" + " " + error.response.data.message?.sexo ||
            ""
        );
      } else {
        toast.error("Erro ao cadastrar desenvolvedor.");
      }
    } finally {
      setBtnLoading(false);
      handleCreateClose();
    }
  }, [startDate, getDesenvolvedores]);

  const updateDesenvolvedores = useCallback(
    async (id) => {
      console.log(startUDate?.toISOString());

      handleUpdateClose();
      toast("Alterando Desenvolvedor...");

      try {
        await api.put(`/desenvolvedores/${id}`, {
          nome: unome.current?.value || "",
          sexo: usexo.current?.value || "",
          data_nascimento: startUDate ? startUDate : "",
          hobby: uhobby.current?.value || "",
          nivel_id: univel_id.current?.value || "",
        });
        getDesenvolvedores();
        toast.success("Desenvolvedor atualizado com sucesso.");
      } catch (error) {
        toast.error(error.response.data.message);
      }
    },
    [startUDate, getDesenvolvedores, handleUpdateClose]
  );

  const deleteDesenvolvedores = useCallback(
    async (id) => {
      handleDelClose();
      toast("Apagando Desenvolvedor...");

      try {
        await api.delete(`/desenvolvedores/${id}`);
        getDesenvolvedores();
        toast.success("Desenvolvedor apagado com sucesso.");
      } catch (error) {
        toast.error(error.response.data.message);
      }
    },
    [getDesenvolvedores, handleDelClose]
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

  function selectNivel() {
    let newNiveis = [];
    if (niveis.length > 0) {
      niveis.map((nivel) => {
        newNiveis.push(
          <option key={nivel.id} value={nivel.id}>
            {nivel.nivel}
          </option>
        );
      });
      setNiveisSelect(newNiveis);
      setSlcDisabled(false);
    }
  }

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
    getDesenvolvedores();
    getNiveis();
  }, [getDesenvolvedores, page]);

  useEffect(() => {
    selectNivel();
  }, [niveis]);

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
          <Breadcrumb.Item active>Desenvolvedores</Breadcrumb.Item>
        </Breadcrumb>

        <Container>
          <Row>
            <Col className="text-center">
              <h3>Desenvolvedores</h3>
            </Col>
            <Col xs={6} className="justify-content-center">
              <InputGroup className="mb-3">
                <Form.Control placeholder="Digite o nome" ref={searchNivel} />
                <Button variant="outline-secondary" onClick={getDesenvolvedores}>
                  <FaSearch />
                </Button>
              </InputGroup>
            </Col>
            <Col className="text-center">
              <Button variant="success" onClick={handleCreateShow}>
                {" "}
                Cadastrar{" "}
              </Button>
            </Col>
          </Row>
        </Container>

        <Table striped hover>
          <thead>
            <tr>
              <th onClick={() => handleSort("id")} className="action">
                #
              </th>
              <th onClick={() => handleSort("nome")} className="action">
                Nome
              </th>
              <th onClick={() => handleSort("sexo")} className="action">
                Sexo
              </th>
              <th
                onClick={() => handleSort("data_nascimento")}
                className="action"
              >
                Nascimento
              </th>
              <th onClick={() => handleSort("hobby")} className="action">
                Hobby
              </th>
              <th onClick={() => handleSort("nivel_id")} className="action">
                Nível
              </th>
              <th>Idade</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {desenvolvedores.map((desenvolvedor) => (
              <tr key={desenvolvedor.id}>
                <td>{desenvolvedor.id}</td>
                <td>{desenvolvedor.nome}</td>
                <td>{desenvolvedor.sexo}</td>
                <td>{
                 (desenvolvedor.data_nascimento).split('-').reverse().join('/')
                }</td>
                <td>{desenvolvedor.hobby}</td>
                <td>{desenvolvedor.nivel.nivel}</td>
                <td>{desenvolvedor.idade}</td>
                <td>
                  <Button
                    variant="danger"
                    onClick={() => handleClickDelete(desenvolvedor.id)}
                  >
                    <FaTrash />
                  </Button>{" "}
                  <Button
                    variant="warning"
                    onClick={() =>
                      handleClickUpdate(
                        desenvolvedor
                      )
                    }
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
            <Col className="text-center"></Col>
            <Col xs={6} className="justify-content-center">
              <Pagination className="justify-content-center">
                {items}
              </Pagination>
            </Col>
            <Col className="text-center"></Col>
          </Row>
        </Container>
        <p id="message" />
        <Modal show={delShow} onHide={handleDelClose}>
          <Modal.Header closeButton>
            <Modal.Title>Excluir Desenvolvedor</Modal.Title>
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
            <Modal.Title>Atualizar Desenvolvedor</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Nome"
              ref={unome}
            />
            <br />
            <Form.Select aria-label="Sexo" ref={usexo}>
              <option>Selecione o Sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </Form.Select>
            <br />
            <div
              className="spinner-border spinner-border-sm"
              role="status"
              hidden={!slcDisabled}
            >
              <span className="visually-hidden">Carregando...</span>
            </div>

            <Form.Select
              aria-label="Nivel"
              ref={univel_id}
              disabled={slcDisabled}
            >
              <option> Selecione o Nível </option>
              {niveisSelect}
            </Form.Select>
            <br />

            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Hobby"
              ref={uhobby}
            />
            <br />

            <DatePicker
              className="picker"
              showIcon
              selected={startUDate} 
              onChange={(date) => getAge(date)}
              locale="ptBR"
              isClearable
              placeholderText="Selecione uma data"
              dateFormat="dd/MM/yyyy"
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

        <Modal show={createShow} onHide={handleCreateClose}>
          <Modal.Header closeButton>
            <Modal.Title>Cadastrar Desenvolvedor</Modal.Title>
          </Modal.Header>
          <Modal.Body className="modalCreate">
            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Nome"
              ref={nome}
            />
            <br />
            <Form.Select aria-label="Sexo" ref={sexo}>
              <option>Selecione o Sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </Form.Select>
            <br />
            <div
              className="spinner-border spinner-border-sm"
              role="status"
              hidden={!slcDisabled}
            >
              <span className="visually-hidden">Carregando...</span>
            </div>

            <Form.Select
              aria-label="Nivel"
              ref={nivel_id}
              disabled={slcDisabled}
            >
              <option> Selecione o Nível </option>
              {niveisSelect}
            </Form.Select>
            <br />

            <Form.Control
              type="text"
              maxLength={50}
              placeholder="Hobby"
              ref={hobby}
            />
            <br />

            <DatePicker
              className="picker"
              showIcon
              selected={startDate}
              onChange={(date) => setStartDate(date)}
              locale="ptBR"
              isClearable
              placeholderText="Clique para selecionar a data"
              dateFormat="dd/MM/yyyy"
            />
          </Modal.Body>
          <Modal.Footer>
            <Button
              type="button"
              onClick={createDesenvolvedores}
              disabled={btnLoading}
            >
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

export { Desenvolvedores };
