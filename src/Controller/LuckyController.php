<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // comment, when stopping to use annotations
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// start
use Easybill\ZUGFeRD\Builder;
use Easybill\ZUGFeRD\Model\Document;
use Easybill\ZUGFeRD\Model\Date;
// use Easybill\ZUGFeRD\Model\DateTime;
use Easybill\ZUGFeRD\Model\Trade\Amount;
// end

#[Route('/lucky')]
class LuckyController extends AbstractController
{

    #[Route('/number/{num<\d+>?100}')]
    public function number(int $num): Response
    {
        $number = random_int(0, $num);
        // start
        $date = new Date(new \DateTime('2022-10-10'));
        $amount = new Amount('1500', 'Euro'); //TODO where to insert?
        
        $doc = new Document(Document::TYPE_COMFORT);
        $doc->getHeader()->setId('RE1337'); // Set invoice No.
        $doc->getHeader()->setName('FirmaXY'); // Set invoice No.
        $doc->getHeader()->setDate($date); // Set invoice No.
//         $doc->getContext()->...
//         $doc->getTrade()->...

        $xml = Builder::create()->getXML($doc);
        echo $xml; // Zugferd XML.
        // end

        return $this->render('lucky/number.html.twig', [
            'number' => $number
        ]);
    }

    #[Route('/number', priority: 3)]
    public function number1(): Response
    {
        $number = random_int(0, 1000);

        return $this->render('lucky/number.html.twig', [
            'number' => $number
        ]);
    }

    #[Route('/number', priority: 7)] // higher priority (here: 7 > 3) sets number2() before number1() when conditions are matching both. default = 0
    public function number2(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number
        ]);
    }
    
    #[Route('/numberos')]
    public function number3(Request $request): Response
    {
        $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');
        
        // use this to get all the available attributes (not only routing ones):
        $allAttributes = $request->attributes->all();
        
        $number = random_int(0, 100);        
        return $this->render('lucky/numberos.html.twig', [
            'numberos' => $number
        ]);
    }
}