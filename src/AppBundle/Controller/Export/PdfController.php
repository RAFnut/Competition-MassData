<?php

namespace AppBundle\Controller\Publik;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;

class PdfController extends Controller
{
	/**
    * @Route("/export/pdf", name="pdf_export")
    */
	public function pdfAction()
	{
		$html = $this->renderView('AppBundle:Publik:pdf.html.twig');
		$pdfGenerator = $this->get('spraed.pdf.generator', 'UTF-8');	

		return new Response($pdfGenerator->generatePDF($html),
                    200,
                    array(
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="out.pdf"'
                    )
    	);
	}

}