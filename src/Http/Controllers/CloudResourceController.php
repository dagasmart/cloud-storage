<?php

namespace DagaSmart\CloudStorage\Http\Controllers;

use Dagasmart\BizAdmin\Renderers\Chart;
use Dagasmart\BizAdmin\Renderers\CRUDCards;
use Dagasmart\BizAdmin\Renderers\CRUDTable;
use Dagasmart\BizAdmin\Renderers\Page;
use DagaSmart\CloudStorage\Services\CloudResourceService;
use DagaSmart\CloudStorage\Services\CloudStorageService;
use Dagasmart\BizAdmin\Renderers\Form;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CloudResourceService $service
 */
class CloudResourceController extends BaseController
{
    protected string $serviceName = CloudResourceService::class;

    /**
     * @throws Exception
     */
    public function index(): JsonResponse|JsonResource
    {
        return $this->response()->success($this->page());
    }

    public function getData(): array
    {
        return $this->service->list();
    }

    /**
     * 这里就是正常的 crud 的内容
     *
     * @throws Exception
     */
    public function list(): Page
    {
        $page = amis()->Page()->data($this->getData())->body(
            $this->view()
        );

        return $this->baseList($page);
    }

    /**
     * 页面css
     *
     * @param string $type
     * @return array
     */
    private function pageCss(string $type = 'page'): array
    {
        $data = [];
        switch ($type) {
            case 'page':
                $data = [
                    '.nav-type > .cxd-Nav-Menu-submenu-title > .cxd-Nav-Menu-item-wrap > .cxd-Nav-Menu-item-link .nav-icon-img > .cxd-Nav-Menu-item-wrap > .cxd-Nav-Menu-item-link' => [
                        'display' => 'flex',
                        'align-items' => 'left',
                    ],
                    '.nav-type .cxd-Nav-Menu-item' => [
                        'margin' => '5px',
                        'border-radius' => '8px',
                        'border' => '1px solid transparent',
                    ],
                    '.nav-type .cxd-Nav-Menu-item-selected' => [
                        'border-radius' => '8px',
                        'background-color' => 'var(--colors-brand-10)',
                        'border' => '1px dashed',
                        'border-left' => '1px solid',
                        'box-shadow' => '1px 1px 2px 1px #0001',
                        'overflow' => 'hidden',
                    ],
                    '.nav-type .cxd-Nav-Menu-item-selected:before' => [
                        'border-right' => '20px solid',
                        'opacity' => '0.1',
                    ],
                    '.nav-type > .cxd-Nav-Menu > .cxd-Nav-Menu-item-tooltip-wrap > .cxd-Nav-Menu-item > .cxd-Nav-Menu-item-wrap > .cxd-Nav-Menu-item-link' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                    ],
                    '.nav-type > .cxd-Nav-Menu-submenu-title > .cxd-Nav-Menu-item-wrap > .cxd-Nav-Menu-item-link > .cxd-Nav-Menu-item-icon' => [
                        'font-size' => '18px',
                    ],
                    '.nav-icon-img > .cxd-Nav-Menu-item-wrap > .cxd-Nav-Menu-item-link > .cxd-Nav-Menu-item-icon >.cxd-Icon' => [
                        'width' => '24px',
                        'height' => 'auto',
                    ],
                    '.nav-icon-img:hover' => [
                        'background' => 'var(--colors-brand-10)',
                    ],
                ];
                break;
            case 'view':
                $data = [
                    '.card-group-page-left' => [
                        'padding-top' => '20px',
                        'margin-left' => '12px',
                        'padding-bottom' => '8px',
                    ],
                    '.card-group-page-right > .cxd-Page-content > .cxd-Page-main > .cxd-Page-body' => [
                        'padding-top' => '20px',
                        'display' => 'flex',
                        'padding-bottom' => '8px',
                    ],
                    '.card-group-page-right > .cxd-Page-content > .cxd-Page-main > .cxd-Page-body > .cxd-Form-item' => [
                        'margin-bottom' => '0',
                    ],
                ];
                break;
            case 'card':
                $data = [
                    '.card-list:hover' => [
                        'background' => 'var(--colors-brand-10)',
                    ],
                    '.card-list > .cxd-Card-heading' => [
                        'padding' => '0',
                        'display' => 'inline-block',
                        'position' => 'absolute',
                        'z-index' => '99',
                    ],
                    '.card-list > .cxd-Card-heading > .cxd-Card-toolbar' => [
                        'margin-left' => '0',
                        'text-align' => 'left',
                    ],
                    '.card-list > .cxd-Card-body' => [
                        'padding' => '0',
                    ],
                    '.card-list > .cxd-Card-body > .cxd-Card-field > .cxd-Card-fieldValue > .cxd-ImageField' => [
                        'display' => 'flex',
                        'justify-content' => 'center',
                    ],
                    '.card-list > .cxd-Card-body > .cxd-Card-field > .cxd-Card-fieldValue > .cxd-Page > .cxd-Page-content > .cxd-Page-main > .cxd-Page-body' => [
                        'display' => 'flex',
                        'flex' => '1',
                        'align-items' => 'center',
                    ],
                    '.card-list-text > .cxd-Page-content > .cxd-Page-main' => [
                        'width' => '100%',
                    ],
                    '.card-list-text > .cxd-Page-content > .cxd-Page-main > .cxd-Page-body > .cxd-TplField > span' => [
                        'width' => '100%',
                        'display' => 'block',
                        'overflow' => 'hidden',
                        'white-space' => 'nowrap',
                        'text-overflow' => 'ellipsis',
                    ],
                    '.card-list > .cxd-Card-heading > .cxd-Card-toolbar > .cxd-Checkbox' => [
                        'padding-left' => '10px',
                    ],
                    '.card-link > .cxd-Button' => [
                        'padding' => '0',
                    ],
                    '.card-list > .cxd-Card-body > .cxd-Card-field > .cxd-Card-fieldValue > .cxd-ImageField > .cxd-Image' => [
                        'width' => '9em',
                        'height' => '9em',
                        'display' => 'flex',
                        'align-items' => 'center',
                    ],
                ];
                break;
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public function page(): Page
    {
        return amis()->Page()->body(
            amis()->Flex()->items([
                amis()->Page()->css($this->pageCss())->className('w-1/5 mr-5')->body([
                    amis()->Card()->body([
                        amis()->Page()->title(cloud_storage_trans('resource_manage'))->css([
                            '.cxd-Page-header' => [
                                'padding' => '0',
                            ], '.cxd-Page-title' => ['font-size' => '16px'],
                        ]),
                        amis()->Divider(),
                        amis()->Nav()->stacked()->defaultOpenLevel('2')->expandPosition('after')
                            ->links([
                                [
                                    'label' => cloud_storage_trans('file_type'),
                                    'icon' => 'fas fa-list',
                                    'active' => true,
                                    'children' => $this->service->generateIcon(),
                                    'className' => 'nav-type',
                                ],
                            ]),
                    ]),
                    amis()->Card()->body([
                        amis()->Flex()->items([
                            amis()->Page()->body([
                                amis()->Page()->bodyClassName('m-auto')->body(cloud_storage_trans('memory_capacity')),
                                amis()->Page()->bodyClassName('m-auto p-0.5 text-xl text-purple-600')->body($this->service->getSizeMemory()),
                            ]),
                            amis()->Divider()->className('m-auto')->direction('vertical'),
                            amis()->Page()->body([
                                amis()->Page()->bodyClassName('m-auto')->body(cloud_storage_trans('quantity')),
                                amis()->Page()->bodyClassName('m-auto p-0.5 text-xl text-purple-600')->body($this->service->getCount()),
                            ]),
                        ]),
                        amis()->Divider(),
                        amis()->Page()->css([
                            '.chart-box' => [
                                'display' => 'flex',
                                'width' => '105% !important',
                            ],
                        ])->body(
                            $this->chart()
                        ),
                    ]),
                ]),
                $this->list(),
            ])
        );
    }

    /**
     * 圆饼
     */
    private function chart(): Chart
    {
        return amis()->Chart()->className('chart-box')->height('200px')->config([
            'grid' => [
                'left' => 0,
                'right' => 0,
                'top' => 0,
                'bottom' => 0,
            ],
            'backgroundColor' => 'transparent',
            'tooltip' => ['show' => true, 'formatter' => 'function (params) {console.log(params.data);return `总计：${params.data.value}<br>${params.data.name}:${params.data.size}`}'],
            'legend' => [
                'show' => true,
                'bottom' => -5,
                'icon' => 'circle',
                'itemWidth' => 6,
                'itemHeight' => 6,
                'left' => 'center',
                'textStyle' => [
                    'fontSize' => '8px',
                ],
            ],
            'color' => ['#1db87b','#fccc5a','#8095ff','#8450ea', '#e6e6e6'],
            'series' => [
                [
                    'type' => 'pie',
                    'radius' => ['40%', '70%'],
                    'avoidLabelOverlap' => false,
                    'label' => [
                        'show' => false,
                        'position' => 'center',
                    ],
                    'emphasis' => [
                        'label' => [
                            'show' => true,
                        ],
                    ],
                    'labelLine' => [
                        'show' => false,
                    ],
                    'itemStyle' => ['borderRadius' => 5, 'borderColor' => 'transparent', 'borderWidth' => 5],
                    'data' => $this->service->getReport(),
                ],
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    public function view(): Page
    {
        return amis()->Page()->className('cxd-Crud shadow-md rounded-md max-h-full overflow-auto')->data(['showType' => 'grid', 'defaultKey' => '1'])->css($this->pageCss('view'))->body([
            amis()->Flex()->items([
                amis()->Page()->id('tabs-list')->className('card-group-page-left w-12')->body([
                    amis()->VanillaAction()->visibleOn('${showType == "grid"}')->icon('fa fa-list')->tooltip(cloud_storage_trans('list'))->tooltipPlacement('top')->onEvent(['click' => ['actions' => [
                        [
                            'actionType' => 'setValue', 'componentId' => 'tabs-list', 'args' => ['value' => ['showType' => 'list']],
                        ],
                        [
                            'actionType' => 'changeActiveKey', 'componentId' => 'tabs-page', 'args' => ['activeKey' => 1],
                        ],
                    ],
                    ],
                    ]),
                    amis()->VanillaAction()->visibleOn('${showType == "list"}')->icon('fa fa-border-all')->tooltip(cloud_storage_trans('grid'))->tooltipPlacement('top')->onEvent(['click' => ['actions' => [
                        [
                            'actionType' => 'setValue', 'componentId' => 'tabs-list', 'args' => ['value' => ['showType' => 'grid']],
                        ],
                        [
                            'actionType' => 'changeActiveKey', 'componentId' => 'tabs-page', 'args' => ['activeKey' => 2],
                        ],
                    ],
                    ],
                    ]),
                ]),
                amis()->Page()->className('card-group-page-right')->body([
                    amis()->VanillaAction()->label(cloud_storage_trans('upload'))->icon('fa fa-arrow-up-from-bracket')
                        ->actionType('dialog')->level('primary')->dialog([
                            'title' => '',
                            'body' => [
                                amis()->SelectControl('storage_id', '存储设置')->selectFirst()
                                    ->options(CloudStorageService::make()->getStorageOptions())->required(),
                                amis()->Button()
                                    ->label('去设置存储')
                                    ->icon('add')
                                    ->level('link')
                                    ->block()
                                    ->actionType('link')
                                    ->link('cloud_storage/storage')
                                    ->visibleOn('${!storage_id}'),
                                amis()->FileControl('file')->labelWidth('0px')
                                    ->btnLabel(cloud_storage_trans('upload'))
                                    ->accept($this->service->getAccept())
                                    ->multiple()->drag()->mode('horizontal')
                                    ->joinValues(false)
                                    ->maxLength(env('CLOUD_STORAGE_FILE_MAX_LENGTH', 10))
//                                    ->maxSize($this->service->getSize())
                                    ->className([
                                        'red' => 'data.progress > 80',
                                        'blue' => 'data.progress > 60',
                                    ])
                                    ->stateTextMap([
                                        'pending'   => '等待上传',
                                        'uploading' => '上传中',
                                        'error'     => '上传出错',
                                        'uploaded'  => '已上传'
                                    ])
                                    ->autoUpload(false)
                                    ->receiver($this->getUploadReceiverPath().'/${storage_id}')
                                    ->startChunkApi($this->getUploadStartChunkPath().'/${storage_id}')
                                    ->chunkApi($this->getUploadChunkPath().'/${storage_id}')
                                    ->finishChunkApi($this->getUploadFinishChunkPath().'/${storage_id}')
                                    ->visibleOn('${!!storage_id}'),
                            ],
                            'actions' => [],
                        ])->reload('window'),
                    amis()->ButtonGroupControl('storage_id')
                        ->multiple(false)
                        ->clearable()
                        ->btnLevel('light')
                        ->btnActiveLevel('warning')
                        //->set('optionType', 'button')
                        ->inputClassName(['p-0' => true])
                        ->className(['pl-5' => true])
                        ->options((new CloudStorageService)->getStorageOptions()),
                    amis()->TextControl()->name('text')->size('lg')->className('card-group-page-left-search')->labelWidth('0px')->mode('horizontal')->addOn(
                        amis()->Button()->label(cloud_storage_trans('query'))->level('primary')
                            ->icon('fas fa-search')->onEvent([
                                'click' => [
                                    'actions' => [
                                        [
                                            'componentId' => 'table_list',
                                            'actionType' => 'reload',
                                            'data' => [
                                                'keyword' => '${text}',
                                                'is_type' => '${is_type}',
                                                'storage_id' => '${storage_id}',
                                            ],
                                        ],
                                        [
                                            'componentId' => 'card_list',
                                            'actionType' => 'reload',
                                            'data' => [
                                                'keyword' => '${text}',
                                                'is_type' => '${is_type}',
                                                'storage_id' => '${storage_id}',
                                            ],
                                        ],
                                    ],
                                ],
                            ])
                    )->placeholder(cloud_storage_trans('keyword_file')),
                ]),
            ]),
            amis()->Page()->css([
                '.tabs-view > .cxd-Tabs-linksContainer-wrapper' => [
                    'display' => 'none',
                ],
            ])->body(
                amis()->Tabs()->id('tabs-page')->className('tabs-view')->activeKey('${activeKey|toInt}')->defaultKey('${defaultKey|toInt}')->tabs([
                    // 表格视图
                    amis()->Tab()->className('table-view')->body(
                        $this->CRUDTablePage()
                    ),
                    // 卡片视图
                    amis()->Tab()->body(
                        $this->CRUDCardsPage()
                    ),
                ])
            ),
        ]);
    }

    private function CRUDTablePage(): CRUDTable
    {
        return amis()->CRUDTable()
            ->perPage(20)
            ->affixHeader(false)
            ->filterTogglable()
            ->id('table_list')
            ->filterDefaultVisible(1)
            ->api($this->getResourceListPath())
            ->bulkActions([$this->bulkDeleteButton()])
            ->headerToolbar([
                'bulkActions',
                amis('reload')->set('align','right'),
            ])
            ->perPageAvailable([10, 20, 30, 50, 100, 200])
            ->footerToolbar(['switch-per-page', 'statistics', 'pagination'])
            ->autoFillHeight(false)
            ->className('min-h-screen')
            ->columns([
                amis()->TableColumn('title', cloud_storage_trans('title')),
                amis()->TableColumn('is_type', '预览')
                    ->type('mapping')->map([
                        'image' => amis()->Image()
                            ->width(60)
                            ->height(60)
                            ->src('${url.value}')
                            ->enlargeAble()
                            ->enlargeWithGallary(false)
                            ->defaultImage('/admin-assets/no-error.svg'),
                        'document' => amis()->Icon()
                            ->icon('far fa-file-archive-o')
                            ->className(['text-warning' => true]),
                        'video' => amis()->Video()
                            ->src('${url.value}')
                            ->className(['text-info' => true])
                            ->style(['zoom' => 0.3]),
                        'audio' => amis()->Audio()
                            ->src('${url.value}')
                            ->controls(['play','process'])
                            ->className(['text-purple-500' => true])
                            ->style(['border' => 'none', 'zoom' => 0.7]),
                        'other' => amis()->Icon()
                            ->icon('far fa-question-circle')
                            ->className(['text-secondary' => true]),
                    ]),
                amis()->TableColumn('extension', '后缀'),
                amis()->TableColumn('size', cloud_storage_trans('file_size')),
                amis()->TableColumn('is_type', cloud_storage_trans('is_type'))->type('mapping')->map([
                    'image' => amis()->Button()->label('图片')->className('shadow')->size('xs')
                                ->style([
                                    'background-color' => '#1db87b10',
                                    'border' => '1px solid #1db87b',
                                    'color' => '#1db87b',
                                    'font-size' => '12px',
                                    'border-radius' => '1rem',
                                    'padding' => '0 .5rem',
                                ]),
                    'document' => amis()->Button()->label('文档')->size('xs')
                                ->style([
                                    'background-color' => '#fccc5a10',
                                    'border' => '1px solid #fccc5a',
                                    'color' => '#fccc5a',
                                    'font-size' => '12px',
                                    'border-radius' => '1rem',
                                    'padding' => '0 .5rem',
                                ]),
                    'video' => amis()->Button()->label('视频')->size('xs')
                                ->style([
                                    'background-color' => '#8095ff10',
                                    'border' => '1px solid #8095ff',
                                    'color' => '#8095ff',
                                    'font-size' => '12px',
                                    'border-radius' => '1rem',
                                    'padding' => '0 .5rem',
                                ]),
                    'audio' => amis()->Button()->label('音频')->size('xs')
                        ->style([
                            'background-color' => '#8450ea10',
                            'border' => '1px solid #8450ea',
                            'color' => '#8450ea',
                            'font-size' => '12px',
                            'border-radius' => '1rem',
                            'padding' => '0 .5rem',
                        ]),
                    'other' => amis()->Button()->label('其他')->size('xs')
                        ->style([
                            'background-color' => '#e6e6e610',
                            'border' => '1px solid #e6e6e6',
                            'color' => '#e6e6e6',
                            'font-size' => '12px',
                            'border-radius' => '1rem',
                            'padding' => '0 .5rem',
                        ]),
                ]),
                amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable(),
                $this->rowActions([
                    $this->rowDeleteButton(),
                ]),
            ]);
    }

    private function CRUDCardsPage(): CRUDCards
    {
        return amis()->CRUDCards()
            ->id('card_list')
            ->perPage(40)
            ->affixHeader(false)
            ->filterTogglable()
            ->filterDefaultVisible(1)
            ->api($this->getResourceListPath())
            ->bulkActions([$this->bulkDeleteButton()])
            ->headerToolbar([
                'bulkActions',
                amis('reload')->set('align','right'),
            ])
            ->set('columnsCount', 5)
            ->perPageAvailable([40, 80, 120, 160, 200, 240])
            ->className('min-h-screen')
            ->footerToolbar(['switch-per-page', 'statistics', 'pagination'])
            ->card(
                amis()->Page()->css($this->pageCss('card'))->body(
                    amis()->Card()->className('card-list')->body([
                        amis()->Image()->visibleOn('${is_type == "other"}')->src($this->service->getIcon('/image/file-type/other.png')),
                        amis()->Image()->visibleOn('${is_type == "image"}')->name('url.value')->thumbRatio('1:1')->enlargeAble(true),
                        amis()->Image()->visibleOn('${is_type == "document"}')->src($this->service->getIcon('/image/file-type/document.png'))->onEvent(['click' => ['actions' => [
                            [
                                'actionType' => 'dialog', 'dialog' => [
                                    'title' => cloud_storage_trans('file_preview'),
                                    'body' => amis()->Page()->body([
                                        amis()->Page()->visibleOn('${event.data.extension == "pdf"}')->body(
                                            amis('pdf-viewer')->id('pdf-viewer')->src('${event.data.url.value}')->width('450'),
                                        ),
                                        amis()->Page()->visibleOn('${event.data.extension == "docx" || event.data.extension == "xlsx" || event.data.extension == "csv" || event.data.extension == "tsv"}')->body(
                                            amis('office-viewer')->id('office-viewer-page')->wordOptions([
                                                'page' => true,
                                            ])->src('${event.data.url.value}')->width('450')
                                        ),
                                    ]),
                                ],
                            ],
                        ],
                        ],
                        ]),
                        amis()->Image()->visibleOn('${is_type == "video"}')->src($this->service->getIcon('/image/file-type/video.png'))->onEvent(['click' => ['actions' => [
                            [
                                'actionType' => 'dialog', 'dialog' => [
                                    'title' => cloud_storage_trans('video_play'),
                                    'body' => amis()->Page()->body(
                                        amis()->Video()->src('${event.data.url.value}')->autoPlay(true)
                                    ),
                                ],
                            ],
                        ],
                        ],
                        ]),
                        amis()->Image()->visibleOn('${is_type == "audio"}')->src($this->service->getIcon('/image/file-type/audio.png')),
                        amis()->Flex()->className('flex-1')->justify('center')->alignItems('center')->items([
                            amis()->Page()->className('flex-auto card-list-text ml-2 text-xs w-1/4 pr-2')->body('${title}'),
                            amis()->DropdownButton()->className('card-link mr-1')->level('link')->icon('fa fa-ellipsis-h')->hideCaret('1')->buttons([
                                amis()->VanillaAction()->label(cloud_storage_trans('rename'))->actionType('dialog')->dialog(['title' => cloud_storage_trans('rename'), 'body' => amis()->Page()->body(
                                    amis()->TextControl('title', cloud_storage_trans('title'))->required(),
                                ), 'actions' => [
                                    [
                                        'type' => 'button',
                                        'actionType' => 'close',
                                        'label' => cloud_storage_trans('close'),
                                    ],
                                    [
                                        'actionType' => 'ajax',
                                        'label' => cloud_storage_trans('confirm'),
                                        'primary' => true,
                                        'type' => 'button',
                                        'api' => [
                                            'url' => $this->updateResourcePath(),
                                            'method' => 'PUT',
                                            'data' => [
                                                'id' => '${id}',
                                                'title' => '${title}',
                                            ],
                                            'messages' => [
                                                'success' => __('admin.save_success'),
                                                'failed' => __('admin.save_failed'),
                                            ],
                                        ],
                                        'close' => true,
                                    ],
                                ]])->closeOnEsc(true),
                                amis()->VanillaAction()->label(cloud_storage_trans('detail'))->actionType('dialog')->dialog(['title' => cloud_storage_trans('detail'), 'body' => amis()->Page()->body([
                                    amis()->Image()->visibleOn('${is_type == "other"}')->src($this->service->getIcon('/image/file-type/other.png')),
                                    amis()->Image()->visibleOn('${is_type == "image"}')->name('url.value')->thumbRatio('16:9')->enlargeAble(true),
                                    amis()->Image()->visibleOn('${is_type == "document"}')->src($this->service->getIcon('/image/file-type/document.png')),
                                    amis()->Image()->visibleOn('${is_type == "video"}')->src($this->service->getIcon('/image/file-type/video.png')),
                                    amis()->Image()->visibleOn('${is_type == "audio"}')->src($this->service->getIcon('/image/file-type/audio.png')),
                                    amis()->TextControl('title', cloud_storage_trans('title'))->static(),
                                    amis()->TextControl('extension', '后缀')->static(),
                                    amis()->TextControl('size', cloud_storage_trans('file_size'))->static(),
                                    amis()->TextControl('created_at', __('admin.created_at'))->static(),
                                    amis()->TextControl('updated_at', __('admin.updated_at'))->static(),
                                ]), 'actions' => []]),
                                //amis()->VanillaAction()->label(cloud_storage_trans('download'))->actionType('download')->api($this->downloadResourcePath()),
                                amis()->DialogAction()->label(__('admin.delete'))->dialog(
                                    amis()->Dialog()
                                        ->title(__('admin.delete'))
                                        ->className('py-2')
                                        ->actions([
                                            amis()->Action()->actionType('cancel')->label(__('admin.cancel')),
                                            amis()->Action()->actionType('submit')->label(__('admin.delete'))->level('danger'),
                                        ])
                                        ->body([
                                            amis()->Form()->wrapWithPanel(false)->api($this->deleteResourcePath())->reload('card_list')->body([
                                                amis()->Tpl()->className('py-2')->tpl(__('admin.confirm_delete')),
                                            ]),
                                        ])
                                ),
                            ]),
                        ]),
                    ])
                )
            );
    }

    public function get_list(): JsonResponse|JsonResource
    {
        return $this->response()->success($this->service->list());
    }

    public function form($isEdit = false): Form
    {
        return $this->baseForm()->body([]);
    }

    public function download($id): JsonResponse|JsonResource
    {
        $detail = $this->service->getDetail($id);
        if (empty($detail)) {
            return $this->response()->fail(cloud_storage_trans('download_failed'));
        }

        //        // 设置响应头
        //        $headers = [
        //            'Content-Type' => Storage::disk('public')->mimeType($detail->url['path']),
        //            'Content-Disposition' => 'attachment; filename="'.urlencode($detail->title).'.'.$detail->extension.'"',
        //        ];
        return $this->response()->success($detail);
        // 返回文件作为响应
        //        return Response::make($detail->url, 200, $headers);
        //        return response()->download($detail->url['value'], $detail->title, [
        //            'Content-Disposition' => 'attachment; filename="'.$detail->title.'"'
        //        ]);
    }
}
