<?php

declare(strict_types=1);

namespace Akeneo\Category\Application\Query;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface GetCategoryChildrenIdsInterface
{
    public function execute(int $categoryId): array;
}
