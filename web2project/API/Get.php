<?php

/*
 * Sample urls:
 *      projects/
 *      projects/283
 *      projects/?limit=10
 *      projects/?limit=10&page=3
 *      projects/?page=2&limit=5
 */

class web2project_API_Get extends web2project_API_Common {

    public function process()
    {
        $status = $this->app->response()->status();
        
        if ($status != 200) {
            return $this->app;
        }

        if ($this->submodule) {
            $fk = unPluralize($this->submodule) . '_'.  unPluralize($this->module);
            $filter = "$fk = {$this->id}";

            $this->processCollection($filter);
            $this->app->response()->body($this->wrapper->getObjectExport($this->output));
            return $this->app;
        }
        if ($this->id) {
            $this->processObject();
            $this->app->response()->body($this->wrapper->getObjectExport($this->output));
            return $this->app;
        } else {
            $this->processCollection();
            $this->app->response()->body($this->wrapper->getObjectExport($this->output));
            return $this->app;
        }

        $this->app->response()->status(404);
        return $this->app;
    }

    protected function processObject()
    {
        $this->obj->loadFull(null, $this->id);
        $this->output->resource = $this->obj;
        $this->output->super_resources = $this->setSuperResources();
        $this->output->sub_resources = $this->setSubResources();
        $this->output->self = '/'.$this->module.'/'.$this->id;
    }

    protected function processCollection($filter = '')
    {
        $page  = (isset($this->params['page']) && (int) $this->params['page'])  ? $this->params['page']  : 0;
        $limit = (isset($this->params['limit']) && (int) $this->params['limit']) ? $this->params['limit'] : 20;

        $this->params['page']  = $page;
        $this->params['limit'] = $limit;

        $collection = $this->obj->loadAll($this->key, $filter);
        $this->output->total = count($collection);
        $maxPage = floor($this->output->total/$this->params['limit']);
        $collection = array_slice($collection, $page*$limit, $limit);

        $this->output->collection = $collection;
        $this->output->count = count($collection);
        $suffix = ($this->id) ? '/' . $this->id . '/' . $this->submodule : '';

        $page = $this->params['page'];
        $this->params['page'] = ($page > 1) ? $page-1 : 0;
        $this->output->prev = '/'.$this->module . $suffix . $this->processParams($this->params);

        $this->params['page'] = ($page < $maxPage) ? $page+1 : $maxPage;
        $this->output->next = '/'.$this->module . $suffix . $this->processParams($this->params);

        $page = $this->params['page'];
        $this->params['page'] = 0;
        $this->output->first = '/'.$this->module . $suffix . $this->processParams($this->params);

        $page = $this->params['page'];
        $this->params['page'] = $maxPage;
        $this->output->last = '/'.$this->module . $suffix . $this->processParams($this->params);
    }
}