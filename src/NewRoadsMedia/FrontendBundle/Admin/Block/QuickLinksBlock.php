<?php

namespace NewRoadsMedia\FrontendBundle\Admin\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class QuickLinksBlock extends BaseBlockService
{
    /** @var bool */
    protected $allowFree = false;

    public function __construct($name, EngineInterface $templating, $allowFree)
    {
        parent::__construct($name, $templating);
        $this->allowFree = $allowFree;
    }

    public function getName()
    {
        return 'Enable Resume Access';
    }

    public function getDefaultSettings()
    {
        return array();
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();

        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        return $this->renderResponse('NewRoadsMediaFrontendBundle:Admin:Block/quickLinks.html.twig', array(
            'block'     => $block,
            'settings'  => $settings,
            'allowFree' => $this->allowFree,
        ), $response);
    }
}