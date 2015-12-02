<?php

namespace Ais\ProdiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\ProdiBundle\Exception\InvalidFormException;
use Ais\ProdiBundle\Form\ProdiType;
use Ais\ProdiBundle\Model\ProdiInterface;


class ProdiController extends FOSRestController
{
    /**
     * List all prodis.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing prodis.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many prodis to return.")
     *
     * @Annotations\View(
     *  templateVar="prodis"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getProdisAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_prodi.prodi.handler')->all($limit, $offset);
    }

    /**
     * Get single Prodi.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Prodi for a given id",
     *   output = "Ais\ProdiBundle\Entity\Prodi",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the prodi is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="prodi")
     *
     * @param int     $id      the prodi id
     *
     * @return array
     *
     * @throws NotFoundHttpException when prodi not exist
     */
    public function getProdiAction($id)
    {
        $prodi = $this->getOr404($id);

        return $prodi;
    }

    /**
     * Presents the form to use to create a new prodi.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newProdiAction()
    {
        return $this->createForm(new ProdiType());
    }
    
    /**
     * Presents the form to use to edit prodi.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProdiBundle:Prodi:editProdi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the prodi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when prodi not exist
     */
    public function editProdiAction($id)
    {
		$prodi = $this->getProdiAction($id);
		
        return array('form' => $this->createForm(new ProdiType(), $prodi), 'prodi' => $prodi);
    }

    /**
     * Create a Prodi from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new prodi from the submitted data.",
     *   input = "Ais\ProdiBundle\Form\ProdiType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProdiBundle:Prodi:newProdi.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postProdiAction(Request $request)
    {
        try {
            $newProdi = $this->container->get('ais_prodi.prodi.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newProdi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_prodi', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing prodi from the submitted data or create a new prodi at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\ProdiBundle\Form\ProdiType",
     *   statusCodes = {
     *     201 = "Returned when the Prodi is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProdiBundle:Prodi:editProdi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the prodi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when prodi not exist
     */
    public function putProdiAction(Request $request, $id)
    {
        try {
            if (!($prodi = $this->container->get('ais_prodi.prodi.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $prodi = $this->container->get('ais_prodi.prodi.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $prodi = $this->container->get('ais_prodi.prodi.handler')->put(
                    $prodi,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $prodi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_prodi', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing prodi from the submitted data or create a new prodi at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\ProdiBundle\Form\ProdiType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProdiBundle:Prodi:editProdi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the prodi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when prodi not exist
     */
    public function patchProdiAction(Request $request, $id)
    {
        try {
            $prodi = $this->container->get('ais_prodi.prodi.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $prodi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_prodi', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Prodi or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ProdiInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($prodi = $this->container->get('ais_prodi.prodi.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $prodi;
    }
    
    public function postUpdateProdiAction(Request $request, $id)
    {
		try {
            $prodi = $this->container->get('ais_prodi.prodi.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $prodi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_prodi', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
