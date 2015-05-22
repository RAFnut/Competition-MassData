<?php

namespace AppBundle\Controller\Export;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use AppBundle\Entity\User;

class CsvController extends Controller
{
	/**
    * @Route("/export/csv", name="export_csv")
    */
    public function csvAction()
    {
        // get the service container to pass to the closure
        $container = $this->container;
        $response = new StreamedResponse(function() use($container) {

            $em = $container->get('doctrine')->getManager();

            // The getExportQuery method returns a query that is used to retrieve
            // all the objects (lines of your csv file) you need. The iterate method
            // is used to limit the memory consumption

            $q = $em->getRepository('AppBundle:User')->createQueryBuilder('j')->getQuery();
            $results = $q->iterate();
            $handle = fopen('php://output', 'w');

            fputcsv($handle, array(
                'email',
                'username',
                'password',
            ), ";");

            while (false !== ($row = $results->next())) {
                // add a line in the csv file. You need to implement a toArray() method
                // to transform your object into an array
                fputcsv($handle, array(
                    $row[0]->getEmail(),
                    $row[0]->getUsername(),
                    $row[0]->getPassword(),
                ), ";");
                // used to limit the memory consumption
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }
}