<?php

namespace DagaSmart\CloudStorage\Traits;

use Dagasmart\BizAdmin\Renderers\PickerControl;
use DagaSmart\CloudStorage\Services\CloudResourceService;

trait UploadPickerTrait
{
    use CloudStorageQueryPathTrait;

    /**
     * iconify 图标选择器
     *
     *
     * @param string $name
     * @param string $label
     * @return PickerControl
     */
    public function uploadPicker(string $name = '', string $label = ''): PickerControl
    {
        $cloudResourceService = new CloudResourceService;
        $schema = amis()->CRUDCards()
            ->perPage(40)
            ->set('columnsCount', 8)
            ->footerToolbar(['statistics', 'pagination'])
            ->filter(
                amis()->Form()->wrapWithPanel(false)->body([
                    amis()->GroupControl()->className('pt-3 pb-3')->body([
                        amis()->TextControl('query')
                            ->size('md')
                            ->value('${'.$name.' || "home"}')
                            ->clearable()
                            ->required(),
                        amis()->Button()
                            ->label(__('admin.search'))
                            ->level('primary')
                            ->actionType('submit')
                            ->icon('fa fa-search'),
                        amis()->UrlAction()
                            ->className('ml-2')
                            ->icon('fa fa-external-link-alt')
                            ->label('Icones')
                            ->blank()
                            ->url('https://icones.js.org/collection/all'),
                    ]),
                ])
            )
            ->card(
                amis()->Card()->body([
                    amis()->Image()->visibleOn('${is_type == "other"}')->src($cloudResourceService->getIcon('/image/file-type/other.png'))->width('auto'),
                    amis()->Image()->visibleOn('${is_type == "image"}')->name('url.value')->enlargeAble(true)->width('auto'),
                    amis()->Image()->visibleOn('${is_type == "document"}')->src($cloudResourceService->getIcon('/image/file-type/document.png'))->width('auto'),
                    amis()->Image()->visibleOn('${is_type == "video"}')->src($cloudResourceService->getIcon('/image/file-type/video.png'))->width('auto'),
                    amis()->Image()->visibleOn('${is_type == "audio"}')->src($cloudResourceService->getIcon('/image/file-type/audio.png'))->width('auto'),
                    amis()->Page()->className('overflow-hidden white-space-nowrap')->body('${title}'),
                ])
            );

        return amis()->PickerControl($name, $label)
            ->pickerSchema($schema)
            ->source($this->getResourceListPath())
            ->size('lg')
            ->labelField('id')
            ->valueField('id');
    }
}
