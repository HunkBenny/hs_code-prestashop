<img width="818" height="71" alt="image" src="https://github.com/user-attachments/assets/17e58599-76a6-4796-bac7-63a561414bcc" />## Description
This plugin adds HS-code functionalities to Prestashop. It adds a new database table where the HS-code is stored.

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
HS-codes can be accessed via the `hs_code` field in the `products` end-point.

