---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Admin management


APIs related to Admin
<!-- START_5d4f8024543e5e5cd4c65294b566be08 -->
## _Create Admin_

Admin store endpoint, returns admin instance along with access_token. !! token required | super_admin

> Example request:

```bash
curl -X POST \
    "http://localhost/api/admins" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"fuad","mobile":"01955555555","email":"fuad@gmail.com","password":"secret123","roles":"['admin:doctor', 'admin:yser]"}'

```

```javascript
const url = new URL(
    "http://localhost/api/admins"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "fuad",
    "mobile": "01955555555",
    "email": "fuad@gmail.com",
    "password": "secret123",
    "roles": "['admin:doctor', 'admin:yser]"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "user_id": 2,
    "name": "fuad",
    "email": "fuad@gmail.com",
    "updated_at": "2020-07-09T20:12:00.000000Z",
    "created_at": "2020-07-09T20:12:00.000000Z",
    "id": 2,
    "token": "5|4k8uIbvxTsdGkL2KF2yA6IA4BL3SkqwBcyWXxYN6C7U9p2sfXzkuDMnmQFwAvh0BpwTHWFpg9I4vI0Hb"
}
```

### HTTP Request
`POST api/admins`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | The name of the admin.
        `mobile` | string |  required  | The mobile required to create user object.
        `email` | string |  required  | The email.
        `password` | string |  required  | The password.
        `roles` | array |  required  | The list of strings defining the roles of the admin.
    
<!-- END_5d4f8024543e5e5cd4c65294b566be08 -->

<!-- START_b3389b14b63a2158bac9354cf37a6ed3 -->
## _Fetch admin roles_

Fetch admin roles list related to sectors of resource in admin panel. !! token required | super_admin

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/admins/roles/load" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/admins/roles/load"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET api/admins/roles/load`


<!-- END_b3389b14b63a2158bac9354cf37a6ed3 -->

<!-- START_666eaa38dc66273f9516ea93ebb6ad94 -->
## Authenticate Admin

Admin login endpoint, returns access_token for admin user

> Example request:

```bash
curl -X POST \
    "http://localhost/api/admins/authenticate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"mobile":"01955555555","password":"secret123"}'

```

```javascript
const url = new URL(
    "http://localhost/api/admins/authenticate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "01955555555",
    "password": "secret123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
"4|Bgl6fz2j3RW4oMZ2mFvrxzbfbHOiScdCmb3jMwyOnhSemIf8eYVJwHnHbVSJ0l2tfG5ClsFulVBeW76A"
```

### HTTP Request
`POST api/admins/authenticate`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `mobile` | string |  required  | The mobile of the user.
        `password` | string |  required  | The password.
    
<!-- END_666eaa38dc66273f9516ea93ebb6ad94 -->

#User management

Admin
APIs related to User
<!-- START_12e37982cc5398c7100e59625ebb5514 -->
## Create/Retrieve User

Get User object using mobile| create new if not present

> Example request:

```bash
curl -X POST \
    "http://localhost/api/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"mobile":"01955555555"}'

```

```javascript
const url = new URL(
    "http://localhost/api/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "01955555555"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
"4|Bgl6fz2j3RW4oMZ2mFvrxzbfbHOiScdCmb3jMwyOnhSemIf8eYVJwHnHbVSJ0l2tfG5ClsFulVBeW76A"
```
> Example response (201):

```json
{
    "mobile": "01955555555",
    "code": "mxH8SeGHt4cjWr8R",
    "updated_at": "2020-07-09T20:44:33.000000Z",
    "created_at": "2020-07-09T20:44:33.000000Z",
    "id": 6,
    "token": "10|gTlkf0Qy4vXkwT51g0BEUqehYEadWonfsKsKPrSnYh7YISkZPFW9DRNZUH0tljrvKAozJTCPgrdtVBnB"
}
```

### HTTP Request
`POST api/users`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `mobile` | string |  required  | The mobile of the user.
    
<!-- END_12e37982cc5398c7100e59625ebb5514 -->

<!-- START_3155db40d08d4838a8141f823bbe5c22 -->
## _Alter User Agent permission_

Change the user object to modify the is_agent & agent_percentage field. !! token required | super_admin, admin:user

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/users/1/agent" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"is_agent":true,"agent_percentage":2.5}'

```

```javascript
const url = new URL(
    "http://localhost/api/users/1/agent"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "is_agent": true,
    "agent_percentage": 2.5
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (400):

```json
"validation error"
```
> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/users/{user}/agent`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `is_agent` | boolean |  required  | Whether user is agent.
        `agent_percentage` | float |  required  | Agent commission percentage for each call.
    
<!-- END_3155db40d08d4838a8141f823bbe5c22 -->

#general


<!-- START_4dfafe7f87ec132be3c8990dd1fa9078 -->
## Return an empty response simply to trigger the storage of the CSRF cookie in the browser.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/sanctum/csrf-cookie" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/sanctum/csrf-cookie"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET sanctum/csrf-cookie`


<!-- END_4dfafe7f87ec132be3c8990dd1fa9078 -->

<!-- START_30afa3387c5c241054df472ff81d21f7 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/checkupprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/checkupprescriptions`


<!-- END_30afa3387c5c241054df472ff81d21f7 -->

<!-- START_a046e7596d2331866d65d4d5d52a1f8c -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/checkupprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/checkupprescriptions`


<!-- END_a046e7596d2331866d65d4d5d52a1f8c -->

<!-- START_4910d6b7772c64fab9f8c89361ccfe40 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/checkupprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Checkupprescription] 1"
}
```

### HTTP Request
`GET api/checkupprescriptions/{checkupprescription}`


<!-- END_4910d6b7772c64fab9f8c89361ccfe40 -->

<!-- START_cde8cefeb59bac7f519f62953f81775e -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/checkupprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/checkupprescriptions/{checkupprescription}`

`PATCH api/checkupprescriptions/{checkupprescription}`


<!-- END_cde8cefeb59bac7f519f62953f81775e -->

<!-- START_ac6a70d7e3d89a93a5d400712372ea26 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/checkupprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/checkupprescriptions/{checkupprescription}`


<!-- END_ac6a70d7e3d89a93a5d400712372ea26 -->

<!-- START_af273bdbdf88160f7fbb041368468d89 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctorappointments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/doctorappointments`


<!-- END_af273bdbdf88160f7fbb041368468d89 -->

<!-- START_a15b397fc9298594c22a99092feb385b -->
## api/doctorappointments
> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctorappointments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/doctorappointments`


<!-- END_a15b397fc9298594c22a99092feb385b -->

<!-- START_2fbae4792f0a1cd7e7a6c81876caba5a -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctorappointments/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Doctorappointment] 1"
}
```

### HTTP Request
`GET api/doctorappointments/{doctorappointment}`


<!-- END_2fbae4792f0a1cd7e7a6c81876caba5a -->

<!-- START_e057691c24e7d5ef846fee1a9cd7ea1d -->
## api/doctorappointments/{doctorappointment}
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctorappointments/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/doctorappointments/{doctorappointment}`

`PATCH api/doctorappointments/{doctorappointment}`


<!-- END_e057691c24e7d5ef846fee1a9cd7ea1d -->

<!-- START_89ffc2b94a1023d9ee75754f125c47f8 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/doctorappointments/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/doctorappointments/{doctorappointment}`


<!-- END_89ffc2b94a1023d9ee75754f125c47f8 -->

<!-- START_774744abc65e28e4368f69ef4798a8f7 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/doctors`


<!-- END_774744abc65e28e4368f69ef4798a8f7 -->

<!-- START_f20841b754b603033ecdbc3f8d10b993 -->
## api/doctors
> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctors" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/doctors`


<!-- END_f20841b754b603033ecdbc3f8d10b993 -->

<!-- START_d2e6f599a5874844f4a0830deeeaef34 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Doctor] 1"
}
```

### HTTP Request
`GET api/doctors/{doctor}`


<!-- END_d2e6f599a5874844f4a0830deeeaef34 -->

<!-- START_44694ba1eafc9d8c78e26c3f52fd7ef6 -->
## api/doctors/{doctor}
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/doctors/{doctor}`

`PATCH api/doctors/{doctor}`


<!-- END_44694ba1eafc9d8c78e26c3f52fd7ef6 -->

<!-- START_7bd1525b5a96db5e2af0ee77c418deb7 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/doctors/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/doctors/{doctor}`


<!-- END_7bd1525b5a96db5e2af0ee77c418deb7 -->

<!-- START_c4bda770fe6f998df9ff9a63ccd874db -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctortypes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[]
```

### HTTP Request
`GET api/doctortypes`


<!-- END_c4bda770fe6f998df9ff9a63ccd874db -->

<!-- START_d049702d90b7287ca2dabb8cd56d2c8e -->
## api/doctortypes
> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctortypes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/doctortypes`


<!-- END_d049702d90b7287ca2dabb8cd56d2c8e -->

<!-- START_ffc64814f5f78fe8edfdd822af8759ea -->
## api/doctortypes/{doctortype}
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctortypes/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Doctortype] 1"
}
```

### HTTP Request
`GET api/doctortypes/{doctortype}`


<!-- END_ffc64814f5f78fe8edfdd822af8759ea -->

<!-- START_5d3590b0ac524d8ef45a9811d4422706 -->
## api/doctortypes/{doctortype}
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctortypes/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/doctortypes/{doctortype}`

`PATCH api/doctortypes/{doctortype}`


<!-- END_5d3590b0ac524d8ef45a9811d4422706 -->

<!-- START_d2d5d4dc996bcec535ea97dc8ebfcd3b -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/doctortypes/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/doctortypes/{doctortype}`


<!-- END_d2d5d4dc996bcec535ea97dc8ebfcd3b -->

<!-- START_42968417170a1c2d0f93430d55b630dc -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patientcheckups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/patientcheckups`


<!-- END_42968417170a1c2d0f93430d55b630dc -->

<!-- START_3004f34a51c1a5bf28d7841387b1bd4e -->
## api/patientcheckups
> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientcheckups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/patientcheckups`


<!-- END_3004f34a51c1a5bf28d7841387b1bd4e -->

<!-- START_af81d850b7324e640d08a2399bc642bd -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patientcheckups/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Patientcheckup] 1"
}
```

### HTTP Request
`GET api/patientcheckups/{patientcheckup}`


<!-- END_af81d850b7324e640d08a2399bc642bd -->

<!-- START_1ea3871cdf18f09b04fdf29186f350c0 -->
## api/patientcheckups/{patientcheckup}
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patientcheckups/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/patientcheckups/{patientcheckup}`

`PATCH api/patientcheckups/{patientcheckup}`


<!-- END_1ea3871cdf18f09b04fdf29186f350c0 -->

<!-- START_ae65bb3401f9581365f40934d74fbf8b -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/patientcheckups/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/patientcheckups/{patientcheckup}`


<!-- END_ae65bb3401f9581365f40934d74fbf8b -->

<!-- START_cdf5e02e9b913556f9304546d59e6c56 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/patients`


<!-- END_cdf5e02e9b913556f9304546d59e6c56 -->

<!-- START_9595666a103e105bb3f677f002653307 -->
## api/patients
> Example request:

```bash
curl -X POST \
    "http://localhost/api/patients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/patients`


<!-- END_9595666a103e105bb3f677f002653307 -->

<!-- START_e21961238df73c8544f00766ed069000 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Patient] 1"
}
```

### HTTP Request
`GET api/patients/{patient}`


<!-- END_e21961238df73c8544f00766ed069000 -->

<!-- START_7b1b54123a6d30586c3e445437e73fd5 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/patients/{patient}`

`PATCH api/patients/{patient}`


<!-- END_7b1b54123a6d30586c3e445437e73fd5 -->

<!-- START_91030317441de3d43a948f7948db4fe7 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/patients/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/patients/{patient}`


<!-- END_91030317441de3d43a948f7948db4fe7 -->

<!-- START_954fd4598f1a1eba859301d487880edb -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patientprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/patientprescriptions`


<!-- END_954fd4598f1a1eba859301d487880edb -->

<!-- START_78bab5eaed235b36f3d6a6372d8efb33 -->
## api/patientprescriptions
> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/patientprescriptions`


<!-- END_78bab5eaed235b36f3d6a6372d8efb33 -->

<!-- START_98217124b67f40430e5d0cb8262ee4a8 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patientprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Patientprescription] 1"
}
```

### HTTP Request
`GET api/patientprescriptions/{patientprescription}`


<!-- END_98217124b67f40430e5d0cb8262ee4a8 -->

<!-- START_fc702686348765d4aa0d7af9dbc5e58b -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patientprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/patientprescriptions/{patientprescription}`

`PATCH api/patientprescriptions/{patientprescription}`


<!-- END_fc702686348765d4aa0d7af9dbc5e58b -->

<!-- START_b82921211ef8558da358c3e51c4e3351 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/patientprescriptions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/patientprescriptions/{patientprescription}`


<!-- END_b82921211ef8558da358c3e51c4e3351 -->

<!-- START_9af0b9f04f16a1c9705c5300772f6f16 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET api/transactions`


<!-- END_9af0b9f04f16a1c9705c5300772f6f16 -->

<!-- START_a524d236dd691776be3315d40786a1db -->
## api/transactions
> Example request:

```bash
curl -X POST \
    "http://localhost/api/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/transactions`


<!-- END_a524d236dd691776be3315d40786a1db -->

<!-- START_b0a0c604cbe4ea6a880e51244ea30727 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/transactions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transactions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "message": "No query results for model [App\\Transaction] 1"
}
```

### HTTP Request
`GET api/transactions/{transaction}`


<!-- END_b0a0c604cbe4ea6a880e51244ea30727 -->

<!-- START_507fdb7fd111d325f1e65ea3af991828 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/transactions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transactions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/transactions/{transaction}`

`PATCH api/transactions/{transaction}`


<!-- END_507fdb7fd111d325f1e65ea3af991828 -->

<!-- START_98b2138fd216945a4b16f2764f45d261 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE \
    "http://localhost/api/transactions/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/transactions/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/transactions/{transaction}`


<!-- END_98b2138fd216945a4b16f2764f45d261 -->


