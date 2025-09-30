## Description
This plugin adds HS-code functionalities to Prestashop. It adds a new database table where the HS-code is stored. Where possible, overrides are avoided.

HS-codes can be set from the Backoffice: <img width="1388" height="297" alt="image" src="https://github.com/user-attachments/assets/c0c76dc6-0441-45e8-a350-3304b53fc245" />
Or via the webservice, using the `products`-endpoint.

### Accessing HS-codes in the invoice templates.
HS-codes are also passed as Smarty variables and are accessible in the invoice templates. For how to change PDF-templates, refer to: https://devdocs.prestashop-project.org/8/modules/concepts/pdf/#invoices
The HS-code can be accessed from an associative array, indexed by the `product_id` to which it corresponds: `$order_invoice->hs_codes[$order_detail['id_product']]`.
For example, you could add the following in the file `pdf/invoice.product-tab.tpl`; 
<img width="818" height="71" alt="image" src="https://github.com/user-attachments/assets/680ecd5a-fd5d-45ad-bb10-1a998ad259ff" />

Which adds this field to the invoice;
<img width="982" height="149" alt="HS-code on invoice" src="https://github.com/user-attachments/assets/835e75be-e696-487a-b941-7d03adad7f1e" />

### HS-code via the webservice
HS-codes can be accessed via the `hs_code` field in the `products` end-point. Unfortunately, no hooks exist in PrestaShop that allow us to extend **existing** endpoints. So, for the webservice to work, an override is added.

## Installation:
Under `versions` you can download the latest version. Simply drag-and-drop this zip-file in the module section of your e-commerce store.

## Tested on versions:
If a PrestaShop version is not in this table, it means it has not been tested yet.
<table><thead><trow><th>Version</th><th>Tested</th></trow></thead><tbody><tr><td>8.2</td><td>✅</td></tr></tbody></table>
