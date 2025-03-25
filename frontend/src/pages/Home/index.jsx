import { useEffect, useState, useRef } from "react";
import "./style.css";
import api from "../../services/api";
import { Toaster, toast } from "sonner";
import Button from "react-bootstrap/Button";
import Table from "react-bootstrap/Table";
import { FaTrash, FaPen } from "react-icons/fa";
import Modal from 'react-bootstrap/Modal';

function Home() {
  const [niveis, setNiveis] = useState([]);
  
  const inputNivel = useRef();
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);
  const [deleteNivelId, setDeleteNivelId] = useState([]);
  const handleClickDelete = (id) => {
    setDeleteNivelId(id);
    setShow(true);
  }

  const handleDeleteItem = () =>{
    deleteNiveis(deleteNivelId);
  }

  async function deleteNiveis(id) {
      handleClose();
      toast("Apagando Nivel ...");
      await api.delete(`/niveis/${id}`);
      getNiveis();
  }

  async function getNiveis() {
    const p = document.getElementById("message");
    toast.info("Carregando...");
    const req = await api.get("/niveis").catch(function (error) {
      if (error) {
        p.textContent = error.response.data.message;
        toast.error(error.response.data.message);
        setNiveis([]);       
      }
    })
    if(req.status === 200){
    p.textContent = "";
    setNiveis(req.data.data);
    
    }
       
  }

  async function createNiveis() {
    toast.info("Cadastrando Niveis");
    await api
      .post("/niveis", {
        nivel: inputNivel.current.value,
      })
      .catch(function (error) {
        if (error.response) {
          console.log(error.response.data.message.nivel);
          toast.error(error.response.data.message.nivel);
        }
      })
    getNiveis();
  }

  useEffect(() => {
    getNiveis();
  }, []);



  return (
    <div className="container">
      <form>
        <h1>Cadastrar Níveis</h1>
        <input
          type="text"
          maxLength={50}
          placeholder="Nível"
          ref={inputNivel}
        ></input>
        <Button type="button" onClick={createNiveis}>
          Cadastrar
        </Button>
      </form>

      <Table striped hover>
        <thead>
          <tr>
            <th>#</th>
            <th>Nível</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        
          {niveis.map((nivel) => (
            <tr key={nivel.id}>
              <td>{nivel.id}</td>
              <td>
                <p>Nível: {nivel.nivel}</p>
              </td>
              <td>
                <Button variant="danger" onClick={() => handleClickDelete(nivel.id)}>
                  <FaTrash />
                </Button>{" "}
                <Button variant="warning">
                  <FaPen></FaPen>
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
      <p id="message"> </p>
      <Modal show={show} onHide={handleClose}>
        <Modal.Header closeButton>
          <Modal.Title>Excluir Nível</Modal.Title>
        </Modal.Header>
        <Modal.Body>Você gostaria de excluir?</Modal.Body>
        <Modal.Footer>
          <Button variant="danger" onClick={ () => handleDeleteItem() }>
            Sim
          </Button>
          <Button variant="primary" onClick={handleClose}>
            Não
          </Button>
        </Modal.Footer>
      </Modal>

      <div className="mensagem">
        <Toaster position="top-right" expand={true} richColors />
      </div>
    </div>
  );
}
export default Home;
