<?php

namespace app\components;
use yii\data\Pagination;
/**
 * CustomPagination is used to force a maximum number of pages.
 * In the Pagination class the pageCount is read-only, in this class it is made
 * read and write property.
 * @property int $pageCount Number of pages. This property is read and write.
 */
class CustomPagination extends Pagination
{
    public $pageCount;

    /**
     * @return int number of pages
     */
    public function getPageCount()
    {
        if (isset($this->pageCount)) {
            return $this->pageCount;
        }
        $pageSize = $this->getPageSize();
        if ($pageSize < 1) {
            return $this->totalCount > 0 ? 1 : 0;
        } else {
            $totalCount = $this->totalCount < 0 ? 0 : (int) $this->totalCount;

            return
            (int) (($totalCount + $pageSize - 1) / $pageSize);
        }
    }

    /**
     * @return int number of pages
     */
    public function setPageCount($maxPages)
    {
        $pageSize = $this->getPageSize();
        if ($pageSize < 1) {
            $this->pageCount = $this->totalCount > 0 ? 1 : 0;
        } else {
            $totalCount = $this->totalCount < 0 ? 0 : (int) $this->totalCount;

            $this->pageCount = (int) (($totalCount + $pageSize - 1) / $pageSize);
        }
    }
}
