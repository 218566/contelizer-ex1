<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileController extends AbstractController
{

    /**
     * @Route("/upload", name="upload_file")
     */
    public function getFile(Request $request) {

        $form = $this->createFormBuilder()
            ->add('file', FileType::class)
            ->add('upload_file', SubmitType::class, ['label' => 'Upload File'])
            ->setAction('/upload')
            ->getForm();

        $form2 = $this->createFormBuilder()
            ->add('go_to_reverse', SubmitType::class, ['label' => 'Go to Reverse'])
            ->setAction('/reverse')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $textFile = $form->getData();
            $original_name = 'test.txt';
            $savedText = $textFile['file'];
            $savedText->move('assets/', $original_name);
        }
        return $this->render('upload-file.html.twig', [
            'upload_file_form' => $form->createView(),
            'go_to_reverse_form' => $form2->createView(),
        ]);

    }

    /**
     * @Route("/reverse", name="reverse_file")
     */
    public function reverseFile() {

        $file = fopen('assets/test.txt','r');

        $fileString = fread($file,filesize('assets/test.txt'));
        $shuffleArray = array();
        $shuffleArrayArrays = array();
        $finishedString = array();
        $finishedArray = array();
        $cyfraPomocnicza = 0;
        $arrayFileStrings = explode(' ', $fileString);
        foreach ($arrayFileStrings as $arrayFileString) {
            $shuffleArray = str_split($arrayFileString);
            $shuffleArrayArrays[] = $shuffleArray;
            $countNumber = count($shuffleArray);
            $countNumber--;
            $tmp = array_slice($shuffleArray,1,$countNumber-1);
            $shuffleTmp = shuffle($tmp);
            $finishedString = $shuffleArrayArrays[$cyfraPomocnicza][0];

            foreach ($tmp as $tmpaas) {
                $finishedString = $finishedString.$tmpaas;
            }
            $finishedString = $finishedString.$shuffleArrayArrays[$cyfraPomocnicza][$countNumber];
            $finishedArray[] = $finishedString;
            $cyfraPomocnicza++;
        }
        $fileString = implode(' ',$finishedArray);
        $fileStringCopy = $fileString;
        file_put_contents('assets/test_copy.txt',$fileString);
        
        return $this->render('reverse-file.html.twig', [
            'reverse_file' => $fileStringCopy,
        ]);
    }
}