<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Persona;

class PersonaController extends AbstractController
{
    public function index()
    {
        return $this->render('persona/index.html.twig', [
            'controller_name' => 'PersonaController',
        ]);
    }

    public function alta()
    {
	    return $this->render('persona/alta.html.twig');
    }

    public function nueva(Request $request)
    {
	    $dni=$request->get('dni');
	    $nombre=$request->get('nombre');
	    $apellidos=$request->get('apellidos');
        $edad=$request->get('edad');
        $pers=new Persona();
	    $pers->setDni($dni);
	    $pers->setNombre($nombre);
        $pers->setApellidos($apellidos);
        $pers->setEdad($edad);	
        $entityManager=$this->getDoctrine()->getManager();
	    $entityManager->persist($pers);
	    $entityManager->flush();
	    return $this->render('persona/nueva.html.twig', [
            'controller_name' => 'PersonaController',
            'dni'=>$pers->getDni(),
        ]);
        }

    public function listado()
    {
        $conexion=$this->getDoctrine()->getConnection();
        $sql="select * from persona";
        $consulta=$conexion->prepare($sql);
        $consulta->execute();
        $resultado=$consulta->fetchAll();
        return $this->render('persona/listado.html.twig', [
            'resultado' => $resultado, 
        ]);
    }

    public function eliminaForm()
    {
        return $this->render('persona/eliminaForm.html.twig');
    }

    public function elimina(Request $request)
    {
        $dni=$request->get('dni');
        
        $personaRepo=$this->getDoctrine()->getRepository(Persona::class);
        $personaDni=$personaRepo->findByDni($dni);
        
        $entityManager=$this->getDoctrine()->getManager();
	    $entityManager->remove($personaDni[0]);
	    $entityManager->flush();
	    return $this->render('persona/elimina.html.twig', [
            'controller_name' => 'PersonaController',
            'dni'=>$dni,
        ]);
    }

    public function modificaForm()
    {
        return $this->render('persona/modificaForm.html.twig');
    }

    public function modifica(Request $request)
    {
        $dni=$request->get('dni');
        
        $personaRepo=$this->getDoctrine()->getRepository(Persona::class);
        $personaDni=$personaRepo->findByDni($dni);
        $pers=$personaDni[0];
        $pers->setNombre('pepito');
        $entityManager=$this->getDoctrine()->getManager();
        $entityManager->persist($pers);
	    $entityManager->flush();
	    return $this->render('persona/modifica.html.twig', [
            'controller_name' => 'PersonaController',
            'dni'=>$dni,
        ]);
    }

}
