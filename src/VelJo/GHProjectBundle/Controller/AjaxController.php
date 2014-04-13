<?php

namespace VelJo\GHProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use VelJo\GHProjectBundle\Controller\HomeWork;

class AjaxController extends Controller
{
    public function ajaxUpdateArticleViewsAction(Request $request){
        $articleId = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $oneArticle = $em->getRepository('VelJoGHProjectBundle:Article')->find($articleId);

        $currentViews = $oneArticle->getViews();
        $currentViews++;

        $oneArticle->setViews($currentViews);
        $em->flush();

        $response = array("code" => 100, "success" => true, "id" => $articleId);

        return new Response(json_encode($response));
    }

    public function ajaxLoadMoreArticleAction($page)
    {
        $pageData = array();

        $limit = 4;
        $offset = ($limit * ($page - 1)) + 1;

        $repository = $this->getDoctrine()->getRepository('VelJoGHProjectBundle:Article');
        $articlesObj = $repository->findArticlesOffsetLimit($offset, $limit);
        $pageData['posts'] = $articlesObj;

        $content = $this->renderView('VelJoGHProjectBundle::Modules/articles_template.html.twig', $pageData);

        return new Response($content);
    }
}