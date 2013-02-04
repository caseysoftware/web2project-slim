
Background
----------------

This started as an attempt to make an API for web2project. It quickly spiraled into something else as I realized that I hate maintaining docs, especially on systems with a lot of extension points and/or add ons. So started playing with OPTIONS.

For context, OPTIONS is covered in RFC 2616 in Section 9.2:

> The OPTIONS method represents a request for information about the communication options available on the request/response chain identified by the Request-URI. This method allows the client to determine the options and/or requirements associated with a resource, or the capabilities of a server, without implying a resource action or initiating a resource retrieval.
>
> Responses to this method are not cacheable.

The full spec: http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html

The premise
----------------

The problem with an API is moving from a) knowing nothing to b) knowing what Resources are available to c) how to interact with those Resources and then finally to d) actually interacting with those Resources.

In general, we use documentation but unfortunately most API documentation sucks.. it's out of date, incomplete, or outright wrong. I believe this is because the documentation is often an afterthought and done in parallel but separately from the API itself. AKA... you open a browser and then your favorite editor and copy/paste/tweak to get started.

But in HATEOAS/hypermedia land, a Resource should be able to describe itself. Further, a Resource should be able to express what other Resource relates to it and how via URIs. And finally, if we consider OPTIONS (info above), a Resource could describe how to interact with itself.


For this proof of concept, I wrote a simple wrapper for my open source project (web2project) where you can begin at the root of the API with an OPTIONS request. It checks the configuration to see which modules (Resources) are available and returns their respective URIs.

From there, you can make an OPTIONS request against any of the Resources to get a representation of the actions available. At the moment, I include: index, create, delete, edit, filter/search, help, and view. Each of those actions defines its own URI pattern, the required http method, and both the required and optional parameters available.

*I defined things in terms of actions instead of http methods because goals, not implementations are key.*

From there, to create a given Resource, you set the required parameters and attempt to create it. If successful, it returns a 201 Created and the fully formed Resource (with the new URI). If the request failed, it returns a 400, the full resource you passed it, and the specific error messages from the validation step. Theoretically, you could "guess" on what is a good request and "learn" from your previous mistakes via these error messages.

In our case with web2project:
*  the required/optional fields are determined by instantiating the object behind the scenes and attempting to validate it. By doing it this way, the validation errors themselves give us the fields.. we don't have to maintain a separate list or doc
*  the inter-Resource relationships are defined by individual property names on the objects, so we can auto-resolve both parent and child resources relative to your requested resource

Some Drawbacks
----------------

-  OPTIONS is not cacheable, which means that any consumer of this API will have to re-request the OPTIONS info upon every new (request? interaction?)
-  A few people have referred to this as "glorified RPC"

Benefits
----------------

-  All documentation is in-band with the API itself, it's difficult for them to drift apart
-  All documentation becomes semi-machine readable completely on the fly
-  Since we now know required/optional fields at runtime, we should be able to decorate our forms (visually and with client-side validation) for individual fields
-  In the case of web2project, if you use our naming conventions for Add On modules, you get this API for free

Longer term ideas
----------------

Now that we have these primitives - index, create, delete, edit, filter/search, help, and view - I would like to come up with "recipes" to describe a user flow through common actions.

TODO
----------------

-  Add more information to the provided fields to describe required datatypes, formats, and (possibly) add human-readable descriptions
-  Provide a variety of output formats/media
-  Make use of accept headers to determine output format
-  Support authentication (Basic auth for now)
-  The required parameters of resources are also appearing in the optional list, that is incorrect
-  On a 201 Created, the new URI should also be in a Location header instead of just in the Resource

DONE
----------------

-  Figure out how to identify and include the super/sub-resources automatically
-  Catch undefined resources (aka ones that don't have classes to map to) before the E_Fatal and return a 400 or 404
-  Better error handling all the way around

Sample
----------------

Results of: curl -X OPTIONS http://localhost/web2project-slim/links

{
    "actions": {
        "create": {
            "href": "/web2project-slim/links", 
            "method": "POST", 
            "optional": [ "link_project", "link_url", "link_task", "link_name", "link_parent", "link_description", "link_owner", "link_date", "link_icon", "link_category" ],
            "required": [ "link_name", "link_url", "link_owner" ]
        }, 
        "delete": {
            "href": "/web2project-slim/links:id", 
            "method": "DELETE", 
            "required": [ "link_id" ]
        }, 
        "edit": {
            "href": "/web2project-slim/links:id", 
            "method": "PATCH", 
            "optional": [ "link_project", "link_url", "link_task", "link_name", "link_parent", "link_description", "link_owner", "link_date", "link_icon", "link_category" ], 
            "required": [
                "link_id"
            ]
        }, 
        "filter": {
            "href": "/web2project-slim/links", 
            "method": "GET", 
            "optional": ["page", "limit"]
        }, 
        "help": {
            "href": "/web2project-slim/links", 
            "method": "OPTIONS"
        }, 
        "index": {
            "href": "/web2project-slim/links", 
            "method": "GET"
        }, 
        "search": {
            "href": "/web2project-slim/links", 
            "method": "GET", 
            "required": [ "search" ]
        }, 
        "view": {
            "href": "/web2project-slim/links:id", 
            "method": "GET", 
            "required": [ "link_id" ]
        }
    }, 
    "resource_uri": "/links", 
    "root_uri": "/web2project-slim", 
    "self": "/web2project-slim/links"
}