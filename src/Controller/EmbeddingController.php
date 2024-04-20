<?php

namespace App\Controller;

use App\Entity\Embedding;
use App\Entity\Word;
use App\Repository\WordRepository;
use App\Service\CosineSimilarity;
use App\Service\Fetch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmbeddingController extends AbstractController
{
    

    #[Route('/embedding', name: 'app_embedding')]
    public function calculateSimilarity(Fetch $service, CosineSimilarity $serviceSimilarity,
                                        EntityManagerInterface $manager, WordRepository $repository, Request $request): Response
    {
        $numberOfWordsInRepo = $repository->count();
        $randomNumber = rand(0, $numberOfWordsInRepo);
// dd($randomNumber);

        $prompt = $request->getPayload()->get('prompt');
        //dd($prompt);
        
//condition if a ajouter
// si id pas existant alors relancer rand
        $secretWord = $repository->findOneBy(['id'=> $randomNumber]);

        foreach($secretWord->getEmbeddings() as $embedding){
            $secretWordEmbedding[] = $embedding->getValue();
        }

        $alreadyEmbeddedPrompt = $repository->findOneBy(['name'=> $prompt]);

        if($alreadyEmbeddedPrompt){
            foreach($alreadyEmbeddedPrompt->getEmbeddings() as $embedding){
                $promptEmbedding[] = $embedding->getValue();
            }
        }else {
            $fetchedData = $service->embbed($prompt);

            $word = new Word();
            $word->setName($prompt);
            $manager->persist($word);
            $manager->flush();

            foreach($fetchedData["embedding"] as $vector){
                $embedding = new Embedding();
                $embedding->setOfWord($word);
                $embedding->setValue($vector);
                $manager->persist($embedding);

                $promptEmbedding[]= $embedding->getValue();
            }
            $manager->flush();
        }

        $similarity = $serviceSimilarity->cosine_similarity($secretWordEmbedding, $promptEmbedding);

        $response = [
//            'secret'=>$secretWord,
            'prompt'=>$prompt,
            'similarity'=>$similarity
        ];


        return $this->json($response, 200);
    }
}
