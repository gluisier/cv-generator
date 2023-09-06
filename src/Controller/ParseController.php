<?php

namespace App\Controller;

use App\Entity AS Entity;
use App\Entity\Translation AS Translation;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParseController extends AbstractController
{
    #[Route("/parse", name: "parse", methods: ["GET","POST"])]
    public function parse(Request $request, ManagerRegistry $doctrine)
    {
        if (!empty($request->request->get('filepath'))) {
            if ($toParse = new \SimpleXMLElement($request->request->get('filepath'), 0, true)) {
                $em = $doctrine->getManager();
                $htmlBody = $toParse->body;
                $meHtml = $htmlBody->header;
                $meAddress = $meHtml->div[1]->address;
                $various = explode(' – ', (string)$meHtml->div[0]->p);
                $me = (new Entity\Person())
                    ->setId($this->getParameter('ME_ID'))
                    ->setFullName((string)$meHtml->div[0]->h1)
                    ->setPosition((string)$meHtml->div[0]->h2)
                    ->setBirthDate(new \DateTime((string)$meHtml->div[0]->p->time->attributes()->datetime))
                    ->setNationality((string)$meHtml->div[0]->p->span)
                    ->setMaritalStatus(end($various))
                    ->setPhone((string)$meHtml->div[1]->p[0]->attributes()->title)
                    ->setEmail((string)$meHtml->div[1]->p[1])
                    ->setSummary(trim((string)$htmlBody->blockquote))
                ;
                $address = (new Entity\Embeddable\Address())
                    ->setStreet((string)$meAddress->span[0])
                    ->setPostalCode((string)$meAddress->span[1])
                    ->setCity((string)$meAddress->span[2])
                    ->setCountry('CHE')
                ;
                $me->setAddress($address);
                $skills = [];
                foreach ($htmlBody->section[0]->div as $category) {
                    if (!$category->h3) {
                        continue;
                    }
                    $categoryData = (new Entity\Skill())
                        ->setName((string)$category->h3)
                    ;
                    $skills[$categoryData->getName()] = $categoryData;
                    $me->addSkill($categoryData);
                    foreach ($category->div->div as $half) {
                        foreach ($half->div as $subcategory) {
                            if ($subcategory->h4) {
                                $subCategoryData = (new Entity\Skill())
                                    ->setName(strip_tags($subcategory->h4->asXML()))
                                    ->setParent($categoryData)
                                ;
                                if (empty($skills[$subCategoryData->getName()])) {
                                    $skills[$subCategoryData->getName()] = $subCategoryData;
                                }
                                $me->addSkill($subCategoryData);
                            }
                            foreach ($subcategory->div as $skill) {
                                if (!($skillData = @$skills[(string)$skill->p])) {
                                    $skillData = (new Entity\Skill())
                                        ->setName((string)$skill->p)
                                        ->setParent($categoryData)
                                    ;
                                    $skills[$skillData->getName()] = $skillData;
                                }
                                $me->addSkill($skillData);
                            }
                        }
                    }
                }
                foreach ($htmlBody->section[1]->div as $experience) {
                    if (!$experience->h3) {
                        continue;
                    }
                    $company = (new Entity\Company())
                        ->setId((string)$experience->attributes()->id)
                        ->setName(strip_tags((string)$experience->p[0]->asXML()))
                    ;
                    $companies[$company->getName()] = $company;
                    $address = (new Entity\Embeddable\Address())
                        ->setCity((string)$experience->p[1])
                    ;
                    $company->setAddress($address);
                    $em->persist($company);
                    $experienceData = (new Entity\Experience())
                        ->setId((string)$experience->attributes()->id)
                        ->setStartDate(new \DateTime((string)$experience->h3->time[0]->attributes()->datetime))
                        ->setEndDate(new \DateTime((string)$experience->h3->time[1]->attributes()->datetime))
                        ->setWhat($experience->p[2] ? strip_tags((string)$experience->p[2]->asXML()) : null)
                        ->setCompany($company)
                    ;
                    $me->addExperience($experienceData);
                    foreach ($experience->details as $realisation) {
                        if ($realisation->details) {
                            foreach ($realisation->details as $description) {
                                $realisationData = (new Entity\Realisation())
                                    ->setName(trim(strip_tags($realisation->summary->asXML())))
                                    ->setLink(($realisation->summary->a ? (string)$realisation->summary->a->attributes()->href : null))
                                    ->setDescription(trim((string)$description->summary))
                                    ->setExperience($experienceData)
                                ;
                                foreach (explode(', ', trim((string)$description->summary->span, '()')) as $i => $technology) {
                                    $technologyData = preg_split('` `', $technology, 2);
                                    if (empty($skills[$technologyData[0]])) {
                                        $skillData = (new Entity\Skill())
                                            ->setName($technologyData[0])
                                        ;
                                        $skills[$technologyData[0]] = $skillData;
                                        $em->persist($skillData);
                                    }
                                    $realisationData->addTechnology(new Entity\Technology($skills[$technologyData[0]], @$technologyData[1] ?? ''));
                                }
                                if ($description->ul) {
                                    foreach ($description->ul->li as $task) {
                                        $realisationData->addTask(strip_tags($task->asXML(), '<i>'));
                                    }
                                }
                            }
                        } else {
                            $realisationData = (new Entity\Realisation())
                                ->setName(trim(strip_tags($realisation->summary->asXML())))
                                ->setLink(($realisation->summary->a ? (string)$realisation->summary->a->attributes()->href : null))
                                ->setDescription(trim((string)$realisation->div))
                                ->setExperience($experienceData)
                            ;
                            if ($description->ul) {
                                foreach ($description->ul->li as $task) {
                                    $realisationData->addTask(strip_tags($task->asXML(), '<i>'));
                                }
                            }
                            foreach (explode(', ', trim((string)$realisation->summary->span, '()')) as $i => $technology) {
                                $technologyData = preg_split('` `', $technology, 2);
                                if (empty($skills[$technologyData[0]])) {
                                    $skillData = (new Entity\Skill())
                                        ->setName($technologyData[0])
                                    ;
                                    $skills[$technologyData[0]] = $skillData;
                                    $em->persist($skillData);
                                }
                                $realisationData->addTechnology(new Entity\Technology($skills[$technologyData[0]], @$technologyData[1] ?? ''));
                            }
                        }
                        $experienceData->addRealisation($realisationData);
                    }
                }
                foreach ($htmlBody->section[2]->div as $training) {
                    if (!$training->h3) {
                        continue;
                    }
                    $address = (new Entity\Embeddable\Address())
                        ->setCity((string)$training->p[1])
                    ;
                    $company = (new Entity\Company())
                        ->setId((string)$training->attributes()->id)
                        ->setName(strip_tags((string)$training->p[0]->asXML()))
                        ->setAddress($address)
                    ;
                    $companies[$company->getName()] = $company;
                    $em->persist($company);
                    $trainingData = (new Entity\Experience())
                        ->setId((string)$training->attributes()->id)
                        ->setStartDate(new \DateTime((string)$training->h3->time[0]->attributes()->datetime))
                        ->setEndDate(new \DateTime((string)$training->h3->time[1]->attributes()->datetime))
                        ->setCompany($company)
                        ->setWhat($training->p[2] ? strip_tags((string)$training->p[2]->asXML()) : null)
                    ;
                    $me->addTraining($trainingData);
                }
                foreach ($htmlBody->section[3]->div as $language) {
                    $languageData = (new Entity\Language())
                        ->setCode((string)$language->attributes()->id)
                        ->setLevel((string)$language->p[0])
                        ->setMeaning($language->p[1] ? trim((string)$language->p[1], '()') : null)
                    ;
                    $me->addLanguage($languageData);
                }
                foreach ($htmlBody->section[4]->div as $person) {
                    $middle =  explode(', ', (string)$person->p[0]);
                    $first = (string)$person->p[1];
                    $second = @(string)$person->p[2];
                    $personData = (new Entity\Person())
                        ->setId((string)$person->attributes()->id)
                        ->setFullName((string)$person->h3)
                        ->setPhone(preg_match('`^\+`', $first) ? $first : (preg_match('`^\+`', $second) ? $second : null))
                        ->setEmail(filter_var($first, FILTER_VALIDATE_EMAIL) ? $first : (filter_var($second, FILTER_VALIDATE_EMAIL) ? $second : null))
                    ;
                    $experience = (new Experience())
                        ->setWhat($middle[0])
                    ;
                    if (@$companies[@$middle[1]]) {
                        $experience->setCompany(@$companies[@$middle[1]] ?? null);
                    }
                    $address = (new Entity\Embeddable\Address())
                        ->setCity('')
                    ;
                    $personData
                        ->setAddress($address)
                        ->addExperience($experience)
                    ;
                    $me->addReferral($personData);
                }
                foreach ($htmlBody->section[5]->div as $hobby) {
                    $hobbyData = (new Entity\Hobby())
                        ->setWhat((string)$hobby->h3)
                        ->setDescription(trim(strip_tags($hobby->p->asXML(), '<a>')))
                    ;
                    $me->addHobby($hobbyData);
                }
                $this->addFlash('success', 'Récupération du XML effectuée');
                $em->persist($me);
                $this->addFlash('success', 'Persistance préparée');
                $em->flush();
                $this->addFlash('success', 'Persistance effectué');
            }
        }

        return $this->render('parse/form.html.twig');
    }

    #[Route("/parse/translation", name: "parse_translation", methods: ["GET","POST"])]
    public function parseTranslation(Request $request, ManagerRegistry $doctrine)
    {
        if (!empty($request->request->get('filepath'))) {
            if ($toParse = new \SimpleXMLElement($request->request->get('filepath'), 0, true)) {
                $em = $doctrine->getManager();
                $htmlBody = $toParse->body;
                $meHtml = $htmlBody->header;
                $meAddress = $meHtml->div[1]->address;
                $various = explode(' – ', (string)$meHtml->div[0]->p);
                $me = (new Translation\Person())
                    ->setPerson($em->getReference(Entity\Person::class, $this->getParameter('ME_ID')))
                    ->setPosition((string)$meHtml->div[0]->h2)
                    ->setNationality((string)$meHtml->div[0]->p->span)
                    ->setMaritalStatus(end($various))
                    ->setSummary(trim((string)$htmlBody->blockquote))
                ;
                $em->persist($me);
                foreach ($htmlBody->section[1]->div as $experience) {
                    if (!$experience->h3) {
                        continue;
                    }
                    $experienceData = (new Translation\Experience())
                        ->setExperience($em->getReference(Entity\Experience::class, (string)$experience->attributes()->id))
                        ->setWhat($experience->p[2] ? strip_tags((string)$experience->p[2]->asXML()) : null)
                    ;
                    $em->persist($experienceData);
                    foreach ($experience->details as $realisation) {
                        if ($realisation->details) {
                            foreach ($realisation->details as $description) {
                                $realisationData = (new Translation\Realisation())
                                    ->setName(trim(strip_tags($realisation->summary->asXML())))
                                    ->setDescription(trim((string)$description->summary))
                                    ->setRealisation($experienceData) // TODO
                                ;
                                if ($description->ul) {
                                    foreach ($description->ul->li as $task) {
                                        $realisationData->addTask(strip_tags($task->asXML(), '<i>'));
                                    }
                                }
                            }
                        } else {
                            $realisationData = (new Translation\Realisation())
                                ->setName(trim(strip_tags($realisation->summary->asXML())))
                                ->setDescription(trim((string)$realisation->div))
                                ->setExperience($experienceData) // TODO
                            ;
                            if ($description->ul) {
                                foreach ($description->ul->li as $task) {
                                    $realisationData->addTask(strip_tags($task->asXML(), '<i>'));
                                }
                            }
                        }
                    }
                }
                foreach ($htmlBody->section[2]->div as $training) {
                    if (!$training->h3) {
                        continue;
                    }
                    $trainingData = (new Translation\Experience())
                        ->setExperience($em->getReference(Entity\Experience::class, (string)$training->attributes()->id))
                        ->setEndDate(new \DateTime((string)$training->h3->time[1]->attributes()->datetime))
                        ->setWhat($training->p[2] ? strip_tags((string)$training->p[2]->asXML()) : null)
                    ;
                    $em->persist($trainingData);
                }
                foreach ($htmlBody->section[3]->div as $language) {
                    $languageData = (new Translation\Language())
                        ->setLanguage($em->getReference(Entity\Language::class, (string)$language->attributes()->id))
                        ->setMeaning($language->p[1] ? trim((string)$language->p[1], '()') : null)
                    ;
                    $em->persist($languageData);
                }
                foreach ($htmlBody->section[5]->div as $hobby) {
                    $hobbyData = (new Translation\Hobby())
                        ->setWhat((string)$hobby->h3)
                        ->setDescription(trim(strip_tags($hobby->p->asXML(), '<a>')))
                    ;
                    $me->addHobby($hobbyData);
                }
                $this->addFlash('success', 'Récupération du XML effectuée');
                $em->persist($me);
                $this->addFlash('success', 'Persistance préparée');
                $em->flush();
                $this->addFlash('success', 'Persistance effectué');
            }
        }

        return $this->render('parse/form.html.twig');
    }
}