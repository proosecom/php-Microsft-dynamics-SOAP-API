# Microsoft dynamics 356 soap API using PHP
Create MS Dynamics cases with SOAP API using PHP. to know more about SOAP API visit 
https://www.proose.com/how-to/create-case-in-ms-dynamics-by-api/

To run the same file on your local server, host the file into the XAMPP or LAMP.

acess the file using localhsot://test/crmtest.php

More about dataype of CRM cases 
https://docs.microsoft.com/en-us/previous-versions/dynamics-crm4/developers-guide/bb956649(v=msdn.10)

**Data types**
```
a:KeyValuePairOfstringanyType>
    <b:key>{Key of String Field}</b:key>
     <b:value i:type="c:string" xmlns:c="http://www.w3.org/2001/XMLSchema">{value}</b:value>
</a:KeyValuePairOfstringanyType>
 
<a:KeyValuePairOfstringanyType>
    <b:key>{Key of String Field}</b:key>
     <b:value i:type="i:int" xmlns:c="http://www.w3.org/2001/XMLSchema">{INT 32 value}</b:value>
</a:KeyValuePairOfstringanyType>
 
 
<a:KeyValuePairOfstringanyType>
    <b:key>{Option set field key}</b:key>";
    <b:value i:type="a:OptionSetValue"> <a:Value>{Picklist value}</a:Value></b:value>
</a:KeyValuePairOfstringanyType>
```
