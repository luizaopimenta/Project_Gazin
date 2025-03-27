import "./style.css";
import Nivel from "../Nivel/index";
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Header from "../../Components/Header/Header";
import { Desenvolvedores } from "../Desenvolvedores";


function Home() {
 

  return(
    <>
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Header />} />
        <Route path='/niveis' element={<Nivel />} />
        <Route path='/desenvolvedores' element={<Desenvolvedores />} />
        <Route path='*' element={<h1>Not Found</h1>} />
      </Routes>
    </BrowserRouter>   
    
    </>
  );

}
export default Home;