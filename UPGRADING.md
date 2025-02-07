# From 1.x to 2.x
This package now supports Plytix's v2 API.
This document outlines the changes made to the package to support the new API.

## Request changes
- Requests targetting Plytix's v1 API have been moved from `Esign\Plytix\Requests` into the `Esign\Plytix\Requests\V1` namespace.
- New requests have been added to target Plytix's v2 API. These requests are located in the `Esign\Plytix\Requests\V2` namespace.
- Deprecated Plytix v1 requests have been removed. You may now use the new v2 requests instead.
    - `Esign\Plytix\Requests\AssignProductsToRelationshipRequest`
    - `Esign\Plytix\Requests\CreateProductRequest`
    - `Esign\Plytix\Requests\LinkAssetToProductRequest`
    - `Esign\Plytix\Requests\ModifySelectedProductAttributesRequest`
    - `Esign\Plytix\Requests\ProductRequest`
    - `Esign\Plytix\Requests\ProductSearchRequest`
    - `Esign\Plytix\Requests\UpdateProductRequest`
- The `Esign\Plytix\Requests\ProductVariantRequest` has been removed.
If you wish to retrieve a list of variants related to a parent, you can do so by using the request to search for all products related to a parent.
- The `Esign\Plytix\Requests\ModifySelectedProductAttributesRequest` has been removed. Use the `Esign\Plytix\Requests\V2\UpdateProductRequest` request instead.

## Response changes
- The `Esign\Plytix\Requests\V2\ProductRequest` now returns product information in a different format.
See [Plytix API docs](https://apidocs.plytix.com/#781906e5-f698-4d79-bb16-3994a7056a35).
- The `Esign\Plytix\Requests\V2\AssignProductsToRelationshipRequest` now returns no content instead of the relationship information.
See [Plytix API docs](https://apidocs.plytix.com/#2f76584a-bd65-438e-a353-302129d0ba25).
- The `Esign\Plytix\Requests\V2\CreateProductRequest` now returns less product information than it used to.
Additionally, it now returns an instance of `Esign\Plytix\DataTransferObjects\V2\Product` instead of an array.
See [Plytix API docs](https://apidocs.plytix.com/#85e380af-7f3b-46bb-8203-3283aef081c2).
- The `Esign\Plytix\Requests\V2\UpdateProductRequest` now returns less product information than it used to.
Additionally, it now returns an instance of `Esign\Plytix\DataTransferObjects\V2\Product` instead of an array.
See [Plytix API docs](https://apidocs.plytix.com/#6317fd74-f753-4a2a-9b28-8752cde0f3d7).
- The `Esign\Plytix\Requests\V2\ProductSearchRequest` now returns product information in a different format.
See [Plytix API docs](https://apidocs.plytix.com/#0e0ad194-7132-49bf-aa8f-951ea1ed266c).

## DataTransferObject changes
These changes only apply if you were casting responses to DTO's using the `dto()` or `dtoOrFail()` methods.

### `Esign\Plytix\DataTransferObjects\V2\Product`
- The `categories` property has been removed. Use the `categoryIds` property instead.
- The `assets` property has been removed. Use the `assetIds` property instead.

### `Esign\Plytix\DataTransferObjects\V2\RelationshipInformation`
- The `relatedProducts` property has been removed. Use the `linksTo` property instead.