web2project-slim
================

This is an attempt to make a proper API using Slim as a foundation.


== TODO ==

-  Provide a variety of output formats/media
-  Make use of accept headers to determine output format
-  Support authentication


== DONE ==

-  Figure out how to identify and include the super/sub-resources automatically
-  Catch undefined resources (aka ones that don't have classes to map to) before the E_Fatal and return a 400 or 404
-  Better error handling all the way around


== OPTIONS ==

One of the places that this differs from a lot of REST-style APIs is the implementation of the OPTIONS verb. I have wired it to give information on the chosen resource. More importantly, behind the scenes, it's actually introspecting the object itself to determine which fields are required, optional, etc. Therefore, regardless of whether someone maintains this specific API, it should stay functional with web2project for quite a while.

The primary reason this works is because we've spent quite a bit fo time refactoring the backend modules to have consistent interfaces throughout. If you have Add On modules installed, *IF* they follow our naming conventions, they should be compatible with this API wrapper out of the box with no further configuration.

But what are the odds of that? ;)


Results of: curl -X OPTIONS http://localhost/web2project-slim/links

{
    "resource":"\/links\/",
    "actions":{
        "index":{"href":"\/links\/","method":"GET"},
        "filter":{"href":"\/links\/","method":"GET", "optional":["page","limit","offset"]},
        "search":{"href":"\/links\/","method":"GET","required":["search"]},
        "view":{"href":"\/links\/:id","method":"GET","required":["link_id"]},
        "create":{
            "href":"\/links\/",
            "method":"POST",
            "required":["link_name","link_url","link_owner"],
            "optional":["link_id","link_project","link_url","link_task","link_name","link_parent","link_description","link_owner","link_date","link_icon","link_category"]
        },
        "update":{
            "href":"\/links\/:id",
            "method":"PATCH",
            "required":"link_id",
            "optional":["link_id","link_project","link_url","link_task","link_name","link_parent","link_description","link_owner","link_date","link_icon","link_category"]},
        "delete":{"href":"\/links\/:id","method":"DELETE","required":"link_id"}
    }
}

Results of: curl -X OPTIONS http://localhost/web2project-slim/companies

{
    "resource":"\/companies\/",
    "actions":{
        "index":{"href":"\/companies\/","method":"GET"},
        "filter":{"href":"\/companies\/","method":"GET","optional":["page","limit","offset"]},
        "search":{"href":"\/companies\/","method":"GET","required":["search"]},
        "view":{"href":"\/companies\/:id","method":"GET","required":["company_id"]},
        "create":{
            "href":"\/companies\/",
            "method":"POST",
            "required":["company_name","company_owner"],
            "optional":["company_id","company_name","company_phone1","company_phone2","company_fax","company_address1","company_address2","company_city","company_state","company_zip","company_country","company_email","company_primary_url","company_owner","company_description","company_type","company_custom"]},
        "update":{
            "href":"\/companies\/:id",
            "method":"PATCH",
            "required":"company_id",
            "optional":["company_id","company_name","company_phone1","company_phone2","company_fax","company_address1","company_address2","company_city","company_state","company_zip","company_country","company_email","company_primary_url","company_owner","company_description","company_type","company_custom"]},
        "delete":{"href":"\/companies\/:id","method":"DELETE","required":"company_id"}
    }
}