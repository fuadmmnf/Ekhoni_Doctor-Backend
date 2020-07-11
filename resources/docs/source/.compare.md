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


> Example response (201):

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

#DoctorAppointment management


APIs related to Scheduled Doctor Appointments
<!-- START_b1411a610bba26baa76f4f3333d16438 -->
## Fetch Active Doctors Appointments Today_

Fetch scheduled valid doctor appointments today.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/et/doctorappointments/today" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/et/doctorappointments/today"
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
[
    {
        "id": 1,
        "doctor_id": 6,
        "patientcheckup_id": 2,
        "code": "fyFDiwwuVU2pzlO8",
        "status": 0,
        "start_time": "2020-07-11 14:19:24",
        "end_time": "2020-07-11 14:40:24",
        "created_at": "2020-07-11T11:51:21.000000Z",
        "updated_at": "2020-07-11T12:18:16.000000Z"
    }
]
```

### HTTP Request
`GET api/doctors/{doctor}/doctorappointments/today`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The doctor id associated with appointments.

<!-- END_b1411a610bba26baa76f4f3333d16438 -->

<!-- START_7fdac91dd25f6c2a3776a481e4afda46 -->
## _Fetch Paginated Doctors Appointments by Status_

Fetch scheduled doctor appointments, paginated response of doctorappointment instances. !! token required| super_admin, admin, doctor

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/velit/doctorappointments/iste" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/velit/doctorappointments/iste"
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
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "doctor_id": 6,
            "patientcheckup_id": 2,
            "code": "fyFDiwwuVU2pzlO8",
            "status": 0,
            "start_time": "2020-07-14 14:19:24",
            "end_time": "2020-07-14 14:40:24",
            "created_at": "2020-07-11T11:51:21.000000Z",
            "updated_at": "2020-07-11T12:18:16.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/6\/doctorappointments\/0?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/6\/doctorappointments\/0?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctors\/6\/doctorappointments\/0",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/doctors/{doctor}/doctorappointments/{status}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The doctor id associated with appointments.
    `status` |  required  | The status to query for the scheduled appointments. 0 => active, 1 => canceled, 2 => completed.

<!-- END_7fdac91dd25f6c2a3776a481e4afda46 -->

<!-- START_a15b397fc9298594c22a99092feb385b -->
## _Create Doctorappointment_

Doctorappointment store endpoint, User must have sufficient balance for doctor rate, returns doctorappointment instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctorappointments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patientcheckup_id":12,"start_time":"\"2020-07-10T14:19:24.000000Z\"","end_time":"\"2020-07-10T14:40:30.000000Z\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "patientcheckup_id": 12,
    "start_time": "\"2020-07-10T14:19:24.000000Z\"",
    "end_time": "\"2020-07-10T14:40:30.000000Z\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "doctor_id": 6,
    "patientcheckup_id": 2,
    "start_time": "2020-07-14T14:19:24.000000Z",
    "end_time": "2020-07-14T14:40:24.000000Z",
    "code": "fyFDiwwuVU2pzlO8",
    "updated_at": "2020-07-11T11:51:21.000000Z",
    "created_at": "2020-07-11T11:51:21.000000Z",
    "id": 1
}
```
> Example response (400):

```json
"User associated with token does not have patient associated with checkup"
```

### HTTP Request
`POST api/doctorappointments`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `patientcheckup_id` | integer |  required  | The patientcheckup id associated with appointment. Frontend must create patientcheckup(with blank start_time and end_time) instance prior to creating doctorappointment.
        `start_time` | string |  required  | The datetime indicating starting time of scheduled appointment.
        `end_time` | string |  required  | The datetime indicating ending time of scheduled appointment.
    
<!-- END_a15b397fc9298594c22a99092feb385b -->

<!-- START_6536576304cd54c3e66e8a4470d70391 -->
## _Update Doctorappointment_

Doctorappointment update, change appointment status. !! token required | doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctorappointments/quibusdam" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":6}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments/quibusdam"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": 6
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/doctorappointments/{doctorappointment}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctorappointment` |  required  | The appointment id.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `status` | integer |  optional  | string Required Indicates status of appointment. 0 => active, 1 => canceled, 2 => finished
    
<!-- END_6536576304cd54c3e66e8a4470d70391 -->

#DoctorType management


APIs related to DoctorTypes
<!-- START_c4bda770fe6f998df9ff9a63ccd874db -->
## Fetch doctor types

Fetch doctor types list.

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
[
    {
        "id": 1,
        "type": 0,
        "specialization": "cardiology",
        "created_at": "2020-07-10T10:09:17.000000Z",
        "updated_at": "2020-07-10T10:09:17.000000Z"
    }
]
```

### HTTP Request
`GET api/doctortypes`


<!-- END_c4bda770fe6f998df9ff9a63ccd874db -->

<!-- START_d049702d90b7287ca2dabb8cd56d2c8e -->
## _Create Doctortype_

Doctortype store endpoint, returns doctortype instance. !! token required | super_admin, admin:doctor

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctortypes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"type":0,"specialization":"\"cardiology\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": 0,
    "specialization": "\"cardiology\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "type": "1",
    "specialization": "cardiology",
    "updated_at": "2020-07-10T12:16:17.000000Z",
    "created_at": "2020-07-10T12:16:17.000000Z",
    "id": 2
}
```

### HTTP Request
`POST api/doctortypes`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `type` | integer |  required  | The type indication of doctor.
        `specialization` | string |  required  | The main field of expertise.
    
<!-- END_d049702d90b7287ca2dabb8cd56d2c8e -->

#Doctor management


APIs related to Doctor
<!-- START_767f45e4b8f4542ff4089a950d6c90c9 -->
## Fetch Paginated Active Doctors By Doctortype

Fetch active doctors, paginated response of doctor instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctortypes/eligendi/doctors/active" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/eligendi/doctors/active"
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
{
    "current_page": 1,
    "data": [
        {
            "id": 6,
            "user_id": 12,
            "doctortype_id": 2,
            "name": "doctorname",
            "bmdc_number": "0000000002",
            "payment_style": 1,
            "activation_status": 1,
            "status": 1,
            "rate": 100,
            "offer_rate": 100,
            "start_time": null,
            "end_time": null,
            "max_appointments_per_day": null,
            "gender": 0,
            "email": "doctor@google.com",
            "workplace": "dmc",
            "designation": "trainee doctor",
            "postgrad": "dmc",
            "medical_college": "dmc",
            "others_training": "sdaosdmoaismdioasmdioas",
            "device_ids": null,
            "booking_start_time": null,
            "created_at": "2020-07-10T15:49:23.000000Z",
            "updated_at": "2020-07-10T16:03:21.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/2\/doctors\/active?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/2\/doctors\/active?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/2\/doctors\/active",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/doctortypes/{doctortype}/doctors/active`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctortype` |  required  | The Doctortype ID of doctors.

<!-- END_767f45e4b8f4542ff4089a950d6c90c9 -->

<!-- START_99afa9e2ef841667ddbc168591601b7f -->
## Fetch Paginated Approved Doctors

Fetch approved doctors, paginated response of doctor instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/approved" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/approved"
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
`GET api/doctors/approved`


<!-- END_99afa9e2ef841667ddbc168591601b7f -->

<!-- START_98917d04220c26cb73782c5a040b2478 -->
## _Fetch Paginated Doctors Requests_

Fetch pending doctor joining requests, paginated response of doctor instances. !! token required| super_admin, admin:doctor

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/pending" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/pending"
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
{
    "current_page": 1,
    "data": [
        {
            "id": 2,
            "user_id": 8,
            "doctortype_id": 2,
            "name": "doctorname",
            "bmdc_number": "0000000000",
            "payment_style": 0,
            "activation_status": 0,
            "status": 0,
            "rate": 100,
            "offer_rate": 100,
            "start_time": null,
            "end_time": null,
            "max_appointments_per_day": null,
            "gender": 0,
            "email": "doctor@google.com",
            "workplace": "dmc",
            "designation": "trainee doctor",
            "postgrad": "dmc",
            "medical_college": "dmc",
            "others_training": "sdaosdmoaismdioasmdioas",
            "device_ids": null,
            "booking_start_time": null,
            "created_at": "2020-07-10T14:19:24.000000Z",
            "updated_at": "2020-07-10T14:19:24.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/pending?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/pending?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctors\/pending",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/doctors/pending`


<!-- END_98917d04220c26cb73782c5a040b2478 -->

<!-- START_f20841b754b603033ecdbc3f8d10b993 -->
## Create Doctor

Doctor store endpoint, returns doctor instance. Doctor instance not approved and payment style depends on customer transaction by default

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctors" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"doctortype_id":20,"name":"vel","bmdc_number":"ratione","rate":3,"offer_rate":6,"gender":10,"mobile":"voluptas","email":"est","workplace":"fugiat","designation":"dolor","medical_college":"adipisci","post_grad":"est","others_training":"maiores","start_time":"\"10:30\"","end_time":"\"3:30\"","max_appointments_per_day":7}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "doctortype_id": 20,
    "name": "vel",
    "bmdc_number": "ratione",
    "rate": 3,
    "offer_rate": 6,
    "gender": 10,
    "mobile": "voluptas",
    "email": "est",
    "workplace": "fugiat",
    "designation": "dolor",
    "medical_college": "adipisci",
    "post_grad": "est",
    "others_training": "maiores",
    "start_time": "\"10:30\"",
    "end_time": "\"3:30\"",
    "max_appointments_per_day": 7
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "user_id": 8,
    "doctortype_id": 2,
    "name": "doctorname",
    "bmdc_number": "0000000000",
    "rate": 100,
    "offer_rate": 100,
    "gender": 0,
    "email": "doctor@google.com",
    "workplace": "dmc",
    "designation": "trainee doctor",
    "medical_college": "dmc",
    "postgrad": "dmc",
    "others_training": "sdaosdmoaismdioasmdioas",
    "updated_at": "2020-07-10T14:19:24.000000Z",
    "created_at": "2020-07-10T14:19:24.000000Z",
    "id": 2
}
```

### HTTP Request
`POST api/doctors`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `doctortype_id` | integer |  required  | The doctortype id.
        `name` | string |  required  | The fullname of doctor.
        `bmdc_number` | string |  required  | The registered bmdc_number of doctor. Unique for doctors.
        `rate` | integer |  required  | The usual rate of doctor per call/appointment.
        `offer_rate` | integer |  optional  | The discounted rate of doctor per call/appointment. If not presen it will be set to usual rate.
        `gender` | integer |  required  | The gender of doctor. 0 => male, 1 => female
        `mobile` | string |  required  | The mobile of doctor. Must be unique across users table.
        `email` | string |  required  | The mail address of doctor.
        `workplace` | string |  required  | The workplace of doctor.
        `designation` | string |  required  | The designation of doctor.
        `medical_college` | string |  required  | The graduation college of doctor.
        `post_grad` | string |  required  | Post Grad degree of doctor [can be blank].
        `others_training` | string |  required  | Other degrees of doctor [can be blank].
        `start_time` | string |  optional  | Duty start time for specialist. Must maintain format.
        `end_time` | string |  optional  | Duty end time for specialist. Must maintain format.
        `max_appointments_per_day` | integer |  optional  | Max number of appointments each day in case of specialist within start-end time.
    
<!-- END_f20841b754b603033ecdbc3f8d10b993 -->

<!-- START_9894223885c982d2265cd4f168dfe315 -->
## _Create Doctor by Admin_

Doctor store endpoint used by admin, returns doctor instance. Doctor instance approved !! token required | super_admin, admin:doctor

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctors/approve" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"doctortype_id":20,"payment_style":20,"name":"quo","bmdc_number":"libero","rate":12,"offer_rate":18,"gender":19,"mobile":"et","email":"harum","workplace":"distinctio","designation":"fugit","medical_college":"quam","post_grad":"quia","others_training":"omnis","start_time":"\"10:30\"","end_time":"\"3:30\"","max_appointments_per_day":5}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/approve"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "doctortype_id": 20,
    "payment_style": 20,
    "name": "quo",
    "bmdc_number": "libero",
    "rate": 12,
    "offer_rate": 18,
    "gender": 19,
    "mobile": "et",
    "email": "harum",
    "workplace": "distinctio",
    "designation": "fugit",
    "medical_college": "quam",
    "post_grad": "quia",
    "others_training": "omnis",
    "start_time": "\"10:30\"",
    "end_time": "\"3:30\"",
    "max_appointments_per_day": 5
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "user_id": 10,
    "doctortype_id": 2,
    "name": "doctorname",
    "bmdc_number": "0000000001",
    "rate": 100,
    "offer_rate": 100,
    "gender": 0,
    "email": "doctor@google.com",
    "workplace": "dmc",
    "designation": "trainee doctor",
    "medical_college": "dmc",
    "others_training": "sdaosdmoaismdioasmdioas",
    "postgrad": "dmc",
    "updated_at": "2020-07-10T14:57:19.000000Z",
    "created_at": "2020-07-10T14:57:19.000000Z",
    "id": 4,
    "activation_status": 1,
    "payment_style": 1
}
```

### HTTP Request
`POST api/doctors/approve`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `doctortype_id` | integer |  required  | The doctortype id.
        `payment_style` | integer |  required  | The payment process of doctor selected by admin. 0 => patient transaction, 1 => paid by organization
        `name` | string |  required  | The fullname of doctor.
        `bmdc_number` | string |  required  | The registered bmdc_number of doctor. Unique for doctors.
        `rate` | integer |  required  | The usual rate of doctor per call/appointment.
        `offer_rate` | integer |  optional  | The discounted rate of doctor per call/appointment. If not presen it will be set to usual rate.
        `gender` | integer |  required  | The gender of doctor. 0 => male, 1 => female
        `mobile` | string |  required  | The mobile of doctor. Must be unique across users table.
        `email` | string |  required  | The mail address of doctor.
        `workplace` | string |  required  | The workplace of doctor.
        `designation` | string |  required  | The designation of doctor.
        `medical_college` | string |  required  | The graduation college of doctor.
        `post_grad` | string |  required  | Post Grad degree of doctor [can be blank].
        `others_training` | string |  required  | Other degrees of doctor [can be blank].
        `start_time` | string |  optional  | Duty start time for specialist. Must maintain format.
        `end_time` | string |  optional  | Duty end time for specialist. Must maintain format.
        `max_appointments_per_day` | integer |  optional  | Max number of appointments each day in case of specialist within start-end time.
    
<!-- END_9894223885c982d2265cd4f168dfe315 -->

<!-- START_572315b3085d226ecbaa3ff74f95441f -->
## _Create Doctor Active Status_

Doctor update active status endpoint used by doctor.!! token required | doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/status" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":15}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/status"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": 15
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
""
```

### HTTP Request
`PUT api/doctors/status`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `status` | integer |  required  | The doctor active status. 0 => inactive, 1 => active
    
<!-- END_572315b3085d226ecbaa3ff74f95441f -->

<!-- START_30f8decf56505b60bc747d9662be6ec9 -->
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


<!-- END_30f8decf56505b60bc747d9662be6ec9 -->

<!-- START_67a7291e52b830e20aa1fbc1d0bd0608 -->
## _Approve Doctor By Admin_

Update doctor activation_status. !! token required| super_admin, admin:doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/vel/approve" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"activation_status":15}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/vel/approve"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "activation_status": 15
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/doctors/{doctor}/approve`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The ID of doctor.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `activation_status` | integer |  required  | The activation indicatior. 0 => not approved, 1 => approved
    
<!-- END_67a7291e52b830e20aa1fbc1d0bd0608 -->

<!-- START_bbc335ee3377c8abc50fab7c54dda238 -->
## _Change Doctor Booking Status_

Update doctor activation_status. !! token required| super_admin, admin:doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/similique/booking" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"booking_start_time":"\"2020-07-10T14:19:24.000000Z\", ''"}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/similique/booking"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "booking_start_time": "\"2020-07-10T14:19:24.000000Z\", ''"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```
> Example response (400):

```json
"another user is currently setting appointment"
```

### HTTP Request
`PUT api/doctors/{doctor}/booking`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The ID of doctor.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `booking_start_time` | string |  required  | The booking starting time for patient. 'date time string' => booking start time, 'blank string' => booking finished.
    
<!-- END_bbc335ee3377c8abc50fab7c54dda238 -->

#Patientcheckup management


APIs related to doctor calls for patient checkup
<!-- START_3004f34a51c1a5bf28d7841387b1bd4e -->
## _Create Patientcheckup_

Patientcheckup store endpoint, User must have sufficient balance for doctor rate, returns patientcheckup instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientcheckups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patient_id":2,"doctor_id":"deserunt","start_time":"\"\", \"2020-07-10T14:19:24.000000Z\"","end_time":"\"\", \"2020-07-10T14:40:30.000000Z\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "patient_id": 2,
    "doctor_id": "deserunt",
    "start_time": "\"\", \"2020-07-10T14:19:24.000000Z\"",
    "end_time": "\"\", \"2020-07-10T14:40:30.000000Z\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "patient_id": 1,
    "doctor_id": 6,
    "start_time": "2020-07-10T21:30:47.000000Z",
    "end_time": null,
    "transaction_id": 5,
    "code": "UenaBBVXuQF2F7A4",
    "updated_at": "2020-07-11T09:46:43.000000Z",
    "created_at": "2020-07-11T09:46:43.000000Z",
    "id": 1
}
```
> Example response (400):

```json
"Insufficient Balance"
```

### HTTP Request
`POST api/patientcheckups`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `patient_id` | integer |  required  | The patient id associated with call.
        `doctor_id` | string |  required  | The doctor id associated with call.
        `start_time` | string |  required  | The datetime indicating starting time of call. Can be set blank to indicate checkup instance for doctorappointment.
        `end_time` | string |  required  | The datetime indicating ending time of call. Can be set blank to indicate start of checkup.
    
<!-- END_3004f34a51c1a5bf28d7841387b1bd4e -->

<!-- START_0ac1f75e0005973e33eab8026cf9a11a -->
## _Update Checkup_

Patientcheckup update patient and doctor ratings and endtime. !! token required | patient, doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patientcheckups/rerum" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"end_time":0,"doctor_rating":6,"patient_rating":12}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/rerum"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "end_time": 0,
    "doctor_rating": 6,
    "patient_rating": 12
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/patientcheckups/{patientcheckup}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patientcheckup` |  required  | The patientcheckup id.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `end_time` | integer |  optional  | string Call end time.
        `doctor_rating` | integer |  optional  | The doctor service rating provided by patient [0-5].
        `patient_rating` | integer |  optional  | The patient behavior rating provided by doctor [0-5].
    
<!-- END_0ac1f75e0005973e33eab8026cf9a11a -->

#Patient management


APIs related to patients
<!-- START_9595666a103e105bb3f677f002653307 -->
## _Create Patient_

Patient store endpoint, returns patient instance with user_id set for User instance associated with token.  !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"enim","age":3,"gender":20,"blood_group":"\"B+ve\"","blood_pressure":"\"90-150\"","cholesterol_level":"\"dont know the readings :p\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/patients"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "enim",
    "age": 3,
    "gender": 20,
    "blood_group": "\"B+ve\"",
    "blood_pressure": "\"90-150\"",
    "cholesterol_level": "\"dont know the readings :p\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "user_id": 3,
    "name": "required",
    "age": 23,
    "gender": 1,
    "code": "RMshPimgOz6yKecP",
    "blood_group": "B+ve",
    "blood_pressure": "90-150",
    "cholesterol_level": "60",
    "updated_at": "2020-07-10T21:30:47.000000Z",
    "created_at": "2020-07-10T21:30:47.000000Z",
    "id": 1
}
```

### HTTP Request
`POST api/patients`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | The patient name.
        `age` | integer |  required  | The patient age.
        `gender` | integer |  required  | The patient gender. 0 => male, 1 => female
        `blood_group` | string |  required  | The patient blood group.
        `blood_pressure` | string |  required  | The patient blood pressure.
        `cholesterol_level` | string |  required  | The patient cholesterol level.
    
<!-- END_9595666a103e105bb3f677f002653307 -->

<!-- START_423bbb3c42a5978f387dafe2fcae2089 -->
## _Update Patient_

Patient update endpoint. User associated with token must match with patient user. !! token required | patient

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patients/est" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"age":8,"blood_group":"\"B+ve\"","blood_pressure":"\"90-150\"","cholesterol_level":"\"dont know the readings :p\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/patients/est"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "age": 8,
    "blood_group": "\"B+ve\"",
    "blood_pressure": "\"90-150\"",
    "cholesterol_level": "\"dont know the readings :p\""
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/patients/{patient}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patient` |  required  | The patient id.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `age` | integer |  required  | The patient age.
        `blood_group` | string |  required  | The patient blood group.
        `blood_pressure` | string |  required  | The patient blood pressure.
        `cholesterol_level` | string |  required  | The patient cholesterol level.
    
<!-- END_423bbb3c42a5978f387dafe2fcae2089 -->

#Patientprescription management


APIs related to patient prescriptions
<!-- START_a3cd4a8305dd303ebdbcb272deee3fcf -->
## _Get Patient Prescriptions_

Fetch patient prescriptinos. !! token required | doctor, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients/reprehenderit/prescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/reprehenderit/prescriptions"
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
[
    {
        "id": 14,
        "patient_id": 1,
        "code": "aQDaDugHpPUndNGN",
        "prescription_path": "assets\/images\/patients\/1\/prescriptions\/aQDaDugHpPUndNGN1594494657.png",
        "created_at": "2020-07-11T19:10:57.000000Z",
        "updated_at": "2020-07-11T19:10:57.000000Z"
    }
]
```

### HTTP Request
`GET api/patients/{patient}/prescriptions`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patient` |  required  | The Id of patient.

<!-- END_a3cd4a8305dd303ebdbcb272deee3fcf -->

<!-- START_22740ea801d09a3619a9d30367a57840 -->
## _Serve Patient Prescription Image_

Fetch patient prescriptino image. !! token required | doctor, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patientprescriptions/14/image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions/14/image"
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


> Example response (403):

```json
"Forbidden Access"
```

### HTTP Request
`GET api/patientprescriptions/{patientprescription}/image`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patientprescription` |  required  | The Id of patientprescription.

<!-- END_22740ea801d09a3619a9d30367a57840 -->

<!-- START_78bab5eaed235b36f3d6a6372d8efb33 -->
## _Store Patientprescription_

Patientprescription store endpoint [Must be multipart/form-data request with image file], User must provide prescription for registered patients, returns patientprescription instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patient_id":18,"prescription":"dolore"}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "patient_id": 18,
    "prescription": "dolore"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "patient_id": 1,
    "code": "aQDaDugHpPUndNGN",
    "prescription_path": "assets\/images\/patients\/1\/prescriptions\/aQDaDugHpPUndNGN1594494657.png",
    "updated_at": "2020-07-11T19:10:57.000000Z",
    "created_at": "2020-07-11T19:10:57.000000Z",
    "id": 14
}
```

### HTTP Request
`POST api/patientprescriptions`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `patient_id` | integer |  required  | The patient id associated with prescription.
        `prescription` | image |  required  | The prescription image of patient.
    
<!-- END_78bab5eaed235b36f3d6a6372d8efb33 -->

#Transaction management


APIs related to Transactions resource
<!-- START_a524d236dd691776be3315d40786a1db -->
## _Create transaction_

Get transaction object after create. user_id of transaction will be set to the user binded with token !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/transactions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"type":17,"amount":17,"status":10}'

```

```javascript
const url = new URL(
    "http://localhost/api/transactions"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": 17,
    "amount": 17,
    "status": 10
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (201):

```json
{
    "user_id": 6,
    "amount": 100,
    "type": 0,
    "status": 0,
    "code": "y4AAAMm3ETJaAwMR",
    "updated_at": "2020-07-10T07:25:02.000000Z",
    "created_at": "2020-07-10T07:25:02.000000Z",
    "id": 2
}
```

### HTTP Request
`POST api/transactions`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `type` | integer |  required  | The type of transaction. 0 => debit, 1 => credit
        `amount` | integer |  required  | The transaction amount.
        `status` | integer |  optional  | The status of transaction, default 0. 0 => initialized(tracked), 1 => completed
    
<!-- END_a524d236dd691776be3315d40786a1db -->

<!-- START_a6af33fe94e80f41594c9cd00e68cd00 -->
## _Update transaction status_

Update transaction status to completed. user_id of transaction has to be same with the user binded with token !! token required | super_admin, admin:transaction, patient

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/transactions/sit/status" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":9}'

```

```javascript
const url = new URL(
    "http://localhost/api/transactions/sit/status"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": 9
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (204):

```json
{}
```

### HTTP Request
`PUT api/transactions/{transaction}/status`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `transaction` |  required  | The ID of the transaction.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `status` | integer |  optional  | The status of transaction, default 0. 0 => initialized(tracked), 1 => completed
    
<!-- END_a6af33fe94e80f41594c9cd00e68cd00 -->

<!-- START_ab49db38738ca64810eedb4f5fd7ade8 -->
## _Fetch Paginated User Completed Transactions_

Fetch completed transaction of user, paginated response. !! token required | super_admin, admin:transaction, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/accusamus/transactions/complete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/accusamus/transactions/complete"
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
`GET api/users/{user}/transactions/complete`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The ID of the user.

<!-- END_ab49db38738ca64810eedb4f5fd7ade8 -->

#User management


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
    "http://localhost/api/users/pariatur/agent" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"is_agent":true,"agent_percentage":2.5}'

```

```javascript
const url = new URL(
    "http://localhost/api/users/pariatur/agent"
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

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The ID of the user.
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


