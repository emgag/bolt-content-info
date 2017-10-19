<?php

namespace Bolt\Extension\Emgag\ContentInfo;

use Bolt\Asset\Widget\Widget;
use Bolt\Extension\SimpleExtension;
use Bolt\Storage\Entity\Content;
use Silex\ControllerCollection;

/**
 * ContentInfo extension class.
 */
class ContentInfoExtension extends SimpleExtension
{

    /**
     * @return string
     */
    public function contentInfo(): string
    {
        $app         = $this->getContainer();
        $id          = $app['request']->attributes->get('id');
        $contentType = $app['request']->attributes->get('contenttypeslug');

        if(!$id || !$contentType){
            return '';
        }

        return $this->renderRecord($contentType, $id);
    }

    /**
     * @param string $contentType
     * @param int    $id
     * @param bool   $panel
     * @return string
     */
    public function renderRecord(string $contentType, int $id, $panel = true): string
    {
        $app = $this->getContainer();
        /** @var \Twig_Environment $twig */
        $twig = $app['twig'];
        /** @var Content $record */
        $record = $app['query']->getContent(implode('/', [$contentType, $id]));

        if (!$record) {
            return '';
        }

        $recordTemplate = $twig->resolveTemplate([
            'content-info/_' . $contentType . '.twig',
            'content-info/_default.twig'
        ]);

        if ($panel) {
            $template = '_edit_content_info_panel.twig';
        } else {
            $template = $recordTemplate;
        }

        return $this->renderTemplate(
            $template,
            [
                'record'         => $record,
                'recordTemplate' => $recordTemplate->getTemplateName(),
            ]
        );
    }

    /**
     * Dynamically update content info panel
     *
     * @param string $contentType
     * @param int    $id
     * @return string
     */
    public function contentInfoAsync(string $contentType, int $id): string
    {
        return $this->renderRecord($contentType, $id, false);
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return ['templates'];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        $assetInfo = new Widget();
        $assetInfo->setCallback([$this, 'contentInfo'])
                  ->setZone('backend')
                  ->setLocation('editcontent_aside_top');

        return [
            $assetInfo
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBackendRoutes(ControllerCollection $collection)
    {
        $collection->match('/content-info/{contentType}/{id}', [$this, 'contentInfoAsync'])->bind('content-info');
    }

}
