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
[
    {
        "id": 2,
        "name": "admin:doctor",
        "guard_name": "web",
        "created_at": "2020-07-09T18:23:36.000000Z",
        "updated_at": "2020-07-09T18:23:36.000000Z"
    }
]
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
<!-- START_993fd9172a1a0a62a5fe33706425f6b5 -->
## _Fetch Upcoming Appointments For User_

Fetch upcoming doctor appointments by user. !! token required| super_admin, admin:patient, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/consequatur/doctorappointments/upcoming" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/consequatur/doctorappointments/upcoming"
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
        "doctor_id": 2,
        "patientcheckup_id": 1,
        "code": "e11hf1h1h81f1f1f",
        "status": 0,
        "start_time": "2020-08-13 20:30:47",
        "end_time": "2020-08-12 14:22:47",
        "created_at": null,
        "updated_at": null,
        "patient": {
            "id": 1,
            "user_id": 5,
            "name": "patient name",
            "code": "aaaaaaaaaa",
            "status": 0,
            "age": "21",
            "gender": 0,
            "address": "asdasdasdasdasdasdasdasd",
            "blood_group": "B -ve",
            "blood_pressure": "100-150",
            "cholesterol_level": "120",
            "height": "5'11''",
            "weight": "90",
            "image": "aaaaaaaaaa_1597428565.png",
            "created_at": "2020-08-09T06:25:34.000000Z",
            "updated_at": "2020-08-14T18:09:26.000000Z"
        },
        "doctor": {
            "id": 2,
            "user_id": 4,
            "doctortype_id": 1,
            "name": "doctorname",
            "bmdc_number": "0000000004",
            "payment_style": 1,
            "activation_status": 1,
            "status": 0,
            "is_featured": 0,
            "rate": 100,
            "offer_rate": 100,
            "first_appointment_rate": 1000,
            "report_followup_rate": null,
            "gender": 0,
            "email": "doctor2@google.com",
            "workplace": "dmc2",
            "designation": "trainee doctor",
            "postgrad": "dmc",
            "medical_college": "dmc",
            "other_trainings": "sdaosdmoaismdioasmdioas",
            "booking_start_time": null,
            "monogram": null,
            "created_at": "2020-08-06T11:24:40.000000Z",
            "updated_at": "2020-08-06T11:24:40.000000Z"
        }
    }
]
```

### HTTP Request
`GET api/users/{user}/doctorappointments/upcoming`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The user id associated with appointments.

<!-- END_993fd9172a1a0a62a5fe33706425f6b5 -->

<!-- START_6b3674b02990958a329b5f98a9bf3406 -->
## _Fetch Paginated Appointments History By User_

Fetch paginated response of completed  doctor appointments by user. !! token required| super_admin, admin:patient, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/doloremque/doctorappointments/history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/doloremque/doctorappointments/history"
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
            "doctor_id": 2,
            "patientcheckup_id": 1,
            "code": "e11hf1h1h81f1f1f",
            "status": 1,
            "start_time": "2020-08-13 20:30:47",
            "end_time": "2020-08-12 14:22:47",
            "created_at": null,
            "updated_at": null,
            "patient": {
                "id": 1,
                "user_id": 5,
                "name": "patient name",
                "code": "aaaaaaaaaa",
                "status": 0,
                "age": "21",
                "gender": 0,
                "address": "asdasdasdasdasdasdasdasd",
                "blood_group": "B -ve",
                "blood_pressure": "100-150",
                "cholesterol_level": "120",
                "height": "5'11''",
                "weight": "90",
                "image": "aaaaaaaaaa_1597428565.png",
                "created_at": "2020-08-09T06:25:34.000000Z",
                "updated_at": "2020-08-14T18:09:26.000000Z"
            },
            "doctor": {
                "id": 2,
                "user_id": 4,
                "doctortype_id": 1,
                "name": "doctorname",
                "bmdc_number": "0000000004",
                "payment_style": 1,
                "activation_status": 1,
                "status": 0,
                "is_featured": 0,
                "rate": 100,
                "offer_rate": 100,
                "first_appointment_rate": 1000,
                "report_followup_rate": null,
                "gender": 0,
                "email": "doctor2@google.com",
                "workplace": "dmc2",
                "designation": "trainee doctor",
                "postgrad": "dmc",
                "medical_college": "dmc",
                "other_trainings": "sdaosdmoaismdioasmdioas",
                "booking_start_time": null,
                "monogram": null,
                "created_at": "2020-08-06T11:24:40.000000Z",
                "updated_at": "2020-08-06T11:24:40.000000Z"
            }
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/users\/5\/doctorappointments\/history?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/users\/5\/doctorappointments\/history?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/users\/5\/doctorappointments\/history",
    "per_page": 20,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/users/{user}/doctorappointments/history`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The user id associated with appointments.

<!-- END_6b3674b02990958a329b5f98a9bf3406 -->

<!-- START_50d589192bb8b5b7e6268f29420deaa5 -->
## _Fetch Paginated Appointments History By Patient_

Fetch paginated response of completed  doctor appointments by patient. !! token required| super_admin, admin:patient, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients/assumenda/doctorappointments/history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/assumenda/doctorappointments/history"
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
            "doctor_id": 2,
            "patientcheckup_id": 1,
            "code": "e11hf1h1h81f1f1f",
            "status": 1,
            "start_time": "2020-08-13 20:30:47",
            "end_time": "2020-08-12 14:22:47",
            "created_at": null,
            "updated_at": null,
            "patient": {
                "id": 1,
                "user_id": 5,
                "name": "patient name",
                "code": "aaaaaaaaaa",
                "status": 0,
                "age": "21",
                "gender": 0,
                "address": "asdasdasdasdasdasdasdasd",
                "blood_group": "B -ve",
                "blood_pressure": "100-150",
                "cholesterol_level": "120",
                "height": "5'11''",
                "weight": "90",
                "image": "aaaaaaaaaa_1597428565.png",
                "created_at": "2020-08-09T06:25:34.000000Z",
                "updated_at": "2020-08-14T18:09:26.000000Z"
            },
            "doctor": {
                "id": 2,
                "user_id": 4,
                "doctortype_id": 1,
                "name": "doctorname",
                "bmdc_number": "0000000004",
                "payment_style": 1,
                "activation_status": 1,
                "status": 0,
                "is_featured": 0,
                "rate": 100,
                "offer_rate": 100,
                "first_appointment_rate": 1000,
                "report_followup_rate": null,
                "gender": 0,
                "email": "doctor2@google.com",
                "workplace": "dmc2",
                "designation": "trainee doctor",
                "postgrad": "dmc",
                "medical_college": "dmc",
                "other_trainings": "sdaosdmoaismdioasmdioas",
                "booking_start_time": null,
                "monogram": null,
                "created_at": "2020-08-06T11:24:40.000000Z",
                "updated_at": "2020-08-06T11:24:40.000000Z"
            }
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/patients\/1\/doctorappointments\/history?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/patients\/1\/doctorappointments\/history?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/patients\/1\/doctorappointments\/history",
    "per_page": 20,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/patients/{patient}/doctorappointments/history`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patient` |  required  | The patient id associated with appointments.

<!-- END_50d589192bb8b5b7e6268f29420deaa5 -->

<!-- START_5d72e24694ab5c136020e1486dd403ae -->
## _Fetch Paginated Upcoming Doctors Appointments_

Fetch scheduled upcoming doctor appointments starting from current date, paginated response of doctorappointment instances. !! token required| super_admin, admin:doctor, doctor

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/minima/doctorappointments/upcoming" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/minima/doctorappointments/upcoming"
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
`GET api/doctors/{doctor}/doctorappointments/upcoming`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The doctor id associated with appointments.

<!-- END_5d72e24694ab5c136020e1486dd403ae -->

<!-- START_7fdac91dd25f6c2a3776a481e4afda46 -->
## _Fetch Paginated Doctors Appointments by Status_

Fetch scheduled doctor appointments, paginated response of doctorappointment instances. !! token required| super_admin, admin:doctor, doctor

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/facere/doctorappointments/voluptates" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/facere/doctorappointments/voluptates"
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

Doctorappointment store endpoint, User must have sufficient balance for doctor rate, must maintain start_time for one of schedule_slots of doctorschedules(changes status of appointment slot to booked), returns doctorappointment instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctorappointments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patient_id":13,"doctor_id":"ab","start_time":"\"2020-07-10T14:19:24.000000Z\"","end_time":"\"2020-07-10T14:40:30.000000Z\""}'

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
    "patient_id": 13,
    "doctor_id": "ab",
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
    `patient_id` | integer |  required  | The patient id associated with call.
        `doctor_id` | string |  required  | The doctor id associated with call.
        `start_time` | string |  required  | The datetime indicating starting time of scheduled appointment.
        `end_time` | string |  required  | The datetime indicating ending time of scheduled appointment.
    
<!-- END_a15b397fc9298594c22a99092feb385b -->

<!-- START_6536576304cd54c3e66e8a4470d70391 -->
## _Update Doctorappointment_

Doctorappointment update, change appointment status. !! token required | doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctorappointments/assumenda" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":7}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctorappointments/assumenda"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": 7
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

#Doctor Schedule management


APIs related to Doctor
<!-- START_0184a88dd5415c34c905f1692a19f698 -->
## Fetch Doctor Schedules By Doctor

Fetch doctor schedules starting from present date to upcoming 30days, Schedule Will not be shown if all slots are booked, response of doctorschedule instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/rerum/doctorschedules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/rerum/doctorschedules"
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
        "doctor_id": 1,
        "start_time": "2020-07-29T18:30:00.000000Z",
        "end_time": "2020-07-29T18:30:00.000000Z",
        "max_appointments_per_day": 4,
        "schedule_slots": "[{\"time\":\"2020-07-29T14:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T15:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T16:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T17:30:00.000000Z\",\"status\":0}]",
        "updated_at": "2020-07-25T21:11:49.000000Z",
        "created_at": "2020-07-25T21:11:49.000000Z",
        "id": 6
    }
]
```

### HTTP Request
`GET api/doctors/{doctor}/doctorschedules`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The Doctor ID of doctor schedules.

<!-- END_0184a88dd5415c34c905f1692a19f698 -->

<!-- START_10c94e313c9c04694802a31fe1d028f3 -->
## _Create Doctorschedule_

Doctorschedule store endpoint(appointment duration set to 20minutes), returns doctorschedule instance. !! token required | admin:doctor, doctor

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctorschedules" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"doctor_id":14,"start_time":"\"2020-07-29T14:30:00.000000Z\"","end_time":"\"2020-07-29T18:30:00.000000Z\""}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctorschedules"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "doctor_id": 14,
    "start_time": "\"2020-07-29T14:30:00.000000Z\"",
    "end_time": "\"2020-07-29T18:30:00.000000Z\""
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
    "doctor_id": 1,
    "start_time": "2020-07-29T14:30:00.000000Z",
    "end_time": "2020-07-29T18:30:00.000000Z",
    "max_appointments_per_day": 4,
    "schedule_slots": "[{\"time\":\"2020-07-29T14:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T15:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T16:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-29T17:30:00.000000Z\",\"status\":0}]",
    "updated_at": "2020-07-25T21:11:49.000000Z",
    "created_at": "2020-07-25T21:11:49.000000Z",
    "id": 6
}
```
> Example response (204):

```json
""
```
> Example response (400):

```json
{
    "message": "Conflicting schedules, failed to create new schedule"
}
```

### HTTP Request
`POST api/doctorschedules`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `doctor_id` | integer |  required  | The doctor id.
        `start_time` | string |  optional  | Duty start time for specialist.
        `end_time` | string |  optional  | Duty end time for specialist.
    
<!-- END_10c94e313c9c04694802a31fe1d028f3 -->

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
        "name": "cardiology",
        "monogram": "image url from server",
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
    -d '{"name":"\"cardiology\""}'

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
    "name": "\"cardiology\""
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
    "name": "cardiology",
    "monogram": null,
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
    `name` | string |  required  | The main field of expertise.
    
<!-- END_d049702d90b7287ca2dabb8cd56d2c8e -->

#Doctor management


APIs related to Doctor
<!-- START_767f45e4b8f4542ff4089a950d6c90c9 -->
## Fetch Paginated Active Doctors By Doctortype

Fetch active doctors, paginated response of doctor instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctortypes/id/doctors/active" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/id/doctors/active"
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
            "is_featured": 0,
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
            "other_trainings": "sdaosdmoaismdioasmdioas",
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

<!-- START_dbde3e5575b577bce32065ec76c16202 -->
## Fetch Paginated Active Doctors

Fetch active doctors, paginated response of doctor instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/active" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/active"
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
            "is_featured": 0,
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
            "other_trainings": "sdaosdmoaismdioasmdioas",
            "device_ids": null,
            "booking_start_time": null,
            "created_at": "2020-07-10T15:49:23.000000Z",
            "updated_at": "2020-07-10T16:03:21.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/active?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/active?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctors\/active",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

### HTTP Request
`GET api/doctors/active`


<!-- END_dbde3e5575b577bce32065ec76c16202 -->

<!-- START_ab2c767f737ec5c8221ce18dfbd67442 -->
## Fetch Paginated Approved Doctors By Doctortype

Fetch approved doctors, paginated response of doctor instances by doctortype.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctortypes/sint/doctors/approved" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctortypes/sint/doctors/approved"
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
            "id": 4,
            "user_id": 10,
            "doctortype_id": 2,
            "name": "doctorname",
            "bmdc_number": "0000000001",
            "payment_style": 1,
            "activation_status": 1,
            "status": 0,
            "is_featured": 0,
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
            "other_trainings": "sdaosdmoaismdioasmdioas",
            "device_ids": null,
            "booking_start_time": null,
            "created_at": "2020-07-10T14:57:19.000000Z",
            "updated_at": "2020-07-10T14:57:19.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/{doctortype}\/doctors\/approved?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/{doctortype}\/doctors\/approved?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctortypes\/{doctortype}\/doctors\/approved",
    "per_page": 10,
    "prev_page_url": null,
    "to": 2,
    "total": 2
}
```

### HTTP Request
`GET api/doctortypes/{doctortype}/doctors/approved`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctortype` |  required  | The Doctortype ID of doctors.

<!-- END_ab2c767f737ec5c8221ce18dfbd67442 -->

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
{
    "current_page": 1,
    "data": [
        {
            "id": 4,
            "user_id": 10,
            "doctortype_id": 2,
            "name": "doctorname",
            "bmdc_number": "0000000001",
            "payment_style": 1,
            "activation_status": 1,
            "status": 0,
            "is_featured": 0,
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
            "other_trainings": "sdaosdmoaismdioasmdioas",
            "device_ids": null,
            "booking_start_time": null,
            "created_at": "2020-07-10T14:57:19.000000Z",
            "updated_at": "2020-07-10T14:57:19.000000Z"
        }
    ],
    "first_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/approved?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/127.0.0.1:8000\/api\/doctors\/approved?page=1",
    "next_page_url": null,
    "path": "http:\/\/127.0.0.1:8000\/api\/doctors\/approved",
    "per_page": 10,
    "prev_page_url": null,
    "to": 2,
    "total": 2
}
```

### HTTP Request
`GET api/doctors/approved`


<!-- END_99afa9e2ef841667ddbc168591601b7f -->

<!-- START_5b6e373b654d547ab09b581c429b4d8c -->
## Fetch All Featured Doctors

Fetch featured doctors, response of doctor instances.

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/featured" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/featured"
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
        "id": 4,
        "user_id": 10,
        "doctortype_id": 2,
        "name": "doctorname",
        "bmdc_number": "0000000001",
        "payment_style": 1,
        "activation_status": 1,
        "status": 0,
        "is_featured": 1,
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
        "other_trainings": "sdaosdmoaismdioasmdioas",
        "device_ids": null,
        "booking_start_time": null,
        "created_at": "2020-07-10T14:57:19.000000Z",
        "updated_at": "2020-07-10T14:57:19.000000Z"
    }
]
```

### HTTP Request
`GET api/doctors/featured`


<!-- END_5b6e373b654d547ab09b581c429b4d8c -->

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
            "is_featured": 0,
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
            "other_trainings": "sdaosdmoaismdioasmdioas",
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
    -d '{"doctortype_id":1,"name":"molestias","bmdc_number":"aut","rate":17,"offer_rate":3,"first_appointment_rate":20,"report_followup_rate":16,"gender":3,"mobile":"sunt","email":"molestiae","workplace":"ad","designation":"necessitatibus","medical_college":"placeat","postgrad":"temporibus","other_trainings":"ipsam","device_id":"voluptas"}'

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
    "doctortype_id": 1,
    "name": "molestias",
    "bmdc_number": "aut",
    "rate": 17,
    "offer_rate": 3,
    "first_appointment_rate": 20,
    "report_followup_rate": 16,
    "gender": 3,
    "mobile": "sunt",
    "email": "molestiae",
    "workplace": "ad",
    "designation": "necessitatibus",
    "medical_college": "placeat",
    "postgrad": "temporibus",
    "other_trainings": "ipsam",
    "device_id": "voluptas"
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
    "other_trainings": "sdaosdmoaismdioasmdioas",
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
        `first_appointment_rate` | integer |  optional  | The initial appointment rate of doctor per patient. If not present it will be set to offer rate.
        `report_followup_rate` | integer |  optional  | The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
        `gender` | integer |  required  | The gender of doctor. 0 => male, 1 => female
        `mobile` | string |  required  | The mobile of doctor. Must be unique across users table.
        `email` | string |  required  | The mail address of doctor.
        `workplace` | string |  required  | The workplace of doctor.
        `designation` | string |  required  | The designation of doctor.
        `medical_college` | string |  required  | The graduation college of doctor.
        `postgrad` | string |  required  | Post Grad degree of doctor [can be blank].
        `other_trainings` | string |  required  | Other degrees of doctor [can be blank].
        `device_id` | string |  optional  | Phone device id for FCM.
    
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
    -d '{"doctortype_id":20,"payment_style":5,"name":"deleniti","bmdc_number":"debitis","rate":1,"offer_rate":17,"first_appointment_rate":9,"report_followup_rate":17,"gender":18,"mobile":"amet","email":"id","workplace":"placeat","designation":"et","medical_college":"voluptatem","postgrad":"laborum","other_trainings":"nam"}'

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
    "payment_style": 5,
    "name": "deleniti",
    "bmdc_number": "debitis",
    "rate": 1,
    "offer_rate": 17,
    "first_appointment_rate": 9,
    "report_followup_rate": 17,
    "gender": 18,
    "mobile": "amet",
    "email": "id",
    "workplace": "placeat",
    "designation": "et",
    "medical_college": "voluptatem",
    "postgrad": "laborum",
    "other_trainings": "nam"
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
    "report_followup_rate": 100,
    "gender": 0,
    "email": "doctor@google.com",
    "workplace": "dmc",
    "designation": "trainee doctor",
    "medical_college": "dmc",
    "other_trainings": "sdaosdmoaismdioasmdioas",
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
        `offer_rate` | integer |  optional  | The discounted rate of doctor per call/appointment. If not present it will be set to usual rate.
        `first_appointment_rate` | integer |  optional  | The initial appointment rate of doctor per patient. If not present it will be set to offer rate.
        `report_followup_rate` | integer |  optional  | The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
        `gender` | integer |  required  | The gender of doctor. 0 => male, 1 => female
        `mobile` | string |  required  | The mobile of doctor. Must be unique across users table.
        `email` | string |  required  | The mail address of doctor.
        `workplace` | string |  required  | The workplace of doctor.
        `designation` | string |  required  | The designation of doctor.
        `medical_college` | string |  required  | The graduation college of doctor.
        `postgrad` | string |  required  | Post Grad degree of doctor [can be blank].
        `other_trainings` | string |  required  | Other degrees of doctor [can be blank].
    
<!-- END_9894223885c982d2265cd4f168dfe315 -->

<!-- START_5f977b566de9d9d4eba3442c1f1f1ba9 -->
## _Change Doctor Image_

Update doctor image (Multipart Request)!! token required | super_admin, admin:doctor, doctor

> Example request:

```bash
curl -X POST \
    "http://localhost/api/doctors/quasi/image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"image":"saepe"}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/quasi/image"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "image": "saepe"
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
"images\/users\/doctors\/1902jid.jpg"
```

### HTTP Request
`POST api/doctors/{doctor}/image`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The ID of the doctor.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `image` | file |  required  | The doctor image file.
    
<!-- END_5f977b566de9d9d4eba3442c1f1f1ba9 -->

<!-- START_572315b3085d226ecbaa3ff74f95441f -->
## _Change Doctor Active Status_

Doctor update active status endpoint used by doctor.!! token required | doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/status" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":1}'

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
    "status": 1
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
## _Update Doctor_

Update doctor attributes !! token required | super_admin, admin:doctor, doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/saepe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"rate":17,"offer_rate":10,"first_appointment_rate":1,"report_followup_rate":19,"workplace":"ab","designation":"odio","other_trainings":"blanditiis"}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/saepe"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "rate": 17,
    "offer_rate": 10,
    "first_appointment_rate": 1,
    "report_followup_rate": 19,
    "workplace": "ab",
    "designation": "odio",
    "other_trainings": "blanditiis"
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
`PUT api/doctors/{doctor}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The ID of the doctor.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `rate` | integer |  required  | The usual rate of doctor per call/appointment.
        `offer_rate` | integer |  optional  | The discounted rate of doctor per call/appointment. If not present it will be set to usual rate.
        `first_appointment_rate` | integer |  optional  | The initial appointment rate of doctor per patient. If not present it will be set to offer rate.
        `report_followup_rate` | integer |  optional  | The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
        `workplace` | string |  required  | The
        `designation` | string |  required  | The designation of doctor.
        `other_trainings` | string |  required  | Other degrees of doctor [can be blank].
    
<!-- END_30f8decf56505b60bc747d9662be6ec9 -->

<!-- START_67a7291e52b830e20aa1fbc1d0bd0608 -->
## _Approve Doctor By Admin_

Update doctor activation_status. !! token required| super_admin, admin:doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/doctors/et/approve" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"activation_status":10}'

```

```javascript
const url = new URL(
    "http://localhost/api/doctors/et/approve"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "activation_status": 10
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

#Patientcheckup management


APIs related to doctor calls for patient checkup
<!-- START_af81d850b7324e640d08a2399bc642bd -->
## api/patientcheckups/{patientcheckup}
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

<!-- START_b8636a5bd1035ce0a5c1a846ac1ed61e -->
## api/doctors/{doctor}/patientcheckups/history
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/1/patientcheckups/history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/1/patientcheckups/history"
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
`GET api/doctors/{doctor}/patientcheckups/history`


<!-- END_b8636a5bd1035ce0a5c1a846ac1ed61e -->

<!-- START_3004f34a51c1a5bf28d7841387b1bd4e -->
## _Create Patientcheckup_

Patientcheckup store endpoint, User must have sufficient balance for doctor rate, returns patientcheckup instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientcheckups" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patient_id":20,"doctor_id":"voluptatibus","start_time":"\"\", \"2020-07-10T14:19:24.000000Z\"","end_time":"\"\", \"2020-07-10T14:40:30.000000Z\""}'

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
    "patient_id": 20,
    "doctor_id": "voluptatibus",
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
        `start_time` | string |  required  | The datetime indicating starting time of call.
        `end_time` | string |  required  | The datetime indicating ending time of call. Can be set blank to indicate start of checkup.
    
<!-- END_3004f34a51c1a5bf28d7841387b1bd4e -->

<!-- START_6352d522e48819a5623885f3212a44a5 -->
## _Create Access Token_

Create Patientcheckup joining information and update to firestore for call notification. !! token required | patient | doctor

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientcheckups/call" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patientcheckup_code":"dicta","is_patientcall":false}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/call"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "patientcheckup_code": "dicta",
    "is_patientcall": false
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
    "access_token": "skadbi1212hdiu92basoicasic",
    "room_name": "demo room",
    "caller_name": "patient name\/ doctor name",
    "checkup_code": "asd1e012jf2f21f",
    "time": "2020-07-11T09:46:43.000000Z"
}
```

### HTTP Request
`POST api/patientcheckups/call`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `patientcheckup_code` | string |  required  | The patientcheckup code for which the call room information is generated
        `is_patientcall` | boolean |  required  | The boolean representation to detect from which end call is generated.
    
<!-- END_6352d522e48819a5623885f3212a44a5 -->

<!-- START_0ac1f75e0005973e33eab8026cf9a11a -->
## _Update Checkup_

Patientcheckup update patient and doctor ratings and endtime. !! token required | patient, doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patientcheckups/eius" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"start_time":"\"2020-07-10T21:45:47.000000Z\"","end_time":"\"2020-07-10T21:45:47.000000Z\"","status":13,"doctor_tags":"sed","patient_tags":"et"}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/eius"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "start_time": "\"2020-07-10T21:45:47.000000Z\"",
    "end_time": "\"2020-07-10T21:45:47.000000Z\"",
    "status": 13,
    "doctor_tags": "sed",
    "patient_tags": "et"
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
`PUT api/patientcheckups/{patientcheckup}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patientcheckup` |  required  | The patientcheckup id.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `start_time` | string |  required  | Call start time.
        `end_time` | string |  required  | Call end time.
        `status` | integer |  required  | Call checkup status. 0=>ongoing, 1=>complete, 2=>incomplete, 3=>not received.
        `doctor_tags` | json_array |  optional  | The doctor service tags.
        `patient_tags` | json_array |  optional  | The patient behavior tags.
    
<!-- END_0ac1f75e0005973e33eab8026cf9a11a -->

<!-- START_9a93aa531cfbb2e6e882ebad94f47ac9 -->
## _Update Patientcheckup status_

Patientcheckup update call status, used if call not picked or ignored. !! token required | patient, doctor

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patientcheckups/quam/call/end" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":15}'

```

```javascript
const url = new URL(
    "http://localhost/api/patientcheckups/quam/call/end"
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
`PUT api/patientcheckups/{patientcheckup}/call/end`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patientcheckup` |  required  | The patientcheckup id.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `status` | integer |  required  | Call status. 0=>ongoing, 1=>complete, 2=>incomplete, 3=>not received
    
<!-- END_9a93aa531cfbb2e6e882ebad94f47ac9 -->

#Patient management


APIs related to patients
<!-- START_d79ac01a88e003529887310fb24ad351 -->
## _Fetch User default Patient Profile_

Fetch default user patient. !! token required | admin:user, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/ipsam/patients/default" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/ipsam/patients/default"
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
`GET api/users/{user}/patients/default`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The User ID of patients.

<!-- END_d79ac01a88e003529887310fb24ad351 -->

<!-- START_4223ef19a0aa7403f15fea4cfe4668b8 -->
## _Fetch Patients By User_

Fetch Patients By User. !! token required | admin:user, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/numquam/patients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/numquam/patients"
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
]
```

### HTTP Request
`GET api/users/{user}/patients`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The User ID of patients.

<!-- END_4223ef19a0aa7403f15fea4cfe4668b8 -->

<!-- START_9595666a103e105bb3f677f002653307 -->
## _Create Patient_

Patient store endpoint, returns patient instance with user_id set for User instance associated with token.  !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patients" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"dignissimos","age":16,"gender":19,"address":"eveniet","blood_group":"\"B+ve\"","blood_pressure":"\"90-150\"","cholesterol_level":"\"dont know the readings :p\"","height":"impedit","weight":"magnam"}'

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
    "name": "dignissimos",
    "age": 16,
    "gender": 19,
    "address": "eveniet",
    "blood_group": "\"B+ve\"",
    "blood_pressure": "\"90-150\"",
    "cholesterol_level": "\"dont know the readings :p\"",
    "height": "impedit",
    "weight": "magnam"
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
null
```

### HTTP Request
`POST api/patients`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | The patient name.
        `age` | integer |  required  | The patient age.
        `gender` | integer |  required  | The patient gender. 0 => male, 1 => female
        `address` | string |  optional  | The patient address.
        `blood_group` | string |  required  | The patient blood group.
        `blood_pressure` | string |  required  | The patient blood pressure.
        `cholesterol_level` | string |  required  | The patient cholesterol level.
        `height` | string |  optional  | The patient height.
        `weight` | string |  optional  | The patient weight.
    
<!-- END_9595666a103e105bb3f677f002653307 -->

<!-- START_89f62d9bfdb2b48c04b5d0b697ee04c7 -->
## _Change Patient Image_

Update patient image (Multipart Request)!! token required | super_admin, admin:user, patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patients/et/image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"image":"vero"}'

```

```javascript
const url = new URL(
    "http://localhost/api/patients/et/image"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "image": "vero"
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
null
```

### HTTP Request
`POST api/patients/{patient}/image`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patient` |  required  | The ID of the patient.
#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `image` | file |  required  | The patient image file.
    
<!-- END_89f62d9bfdb2b48c04b5d0b697ee04c7 -->

<!-- START_423bbb3c42a5978f387dafe2fcae2089 -->
## _Update Patient_

Patient update endpoint. User associated with token must match with patient user. !! token required | patient

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/patients/dolor" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"adipisci","address":"aliquam","age":"reiciendis","blood_group":"\"B+ve\"","blood_pressure":"\"90-150\"","cholesterol_level":"\"dont know the readings :p\"","height":"facere","weight":"ratione"}'

```

```javascript
const url = new URL(
    "http://localhost/api/patients/dolor"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "adipisci",
    "address": "aliquam",
    "age": "reiciendis",
    "blood_group": "\"B+ve\"",
    "blood_pressure": "\"90-150\"",
    "cholesterol_level": "\"dont know the readings :p\"",
    "height": "facere",
    "weight": "ratione"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
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
    `name` | string |  optional  | The patient name.
        `address` | string |  optional  | The patient address.
        `age` | string |  optional  | The patient age.
        `blood_group` | string |  optional  | The patient blood group.
        `blood_pressure` | string |  optional  | The patient blood pressure.
        `cholesterol_level` | string |  optional  | The patient cholesterol level.
        `height` | string |  optional  | The patient height.
        `weight` | string |  optional  | The patient weight.
    
<!-- END_423bbb3c42a5978f387dafe2fcae2089 -->

#Patientprescription management


APIs related to patient prescriptions
<!-- START_a3cd4a8305dd303ebdbcb272deee3fcf -->
## _Get Patient Prescriptions_

Fetch patient prescriptinos. !! token required | doctor, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients/nihil/prescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/nihil/prescriptions"
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
    -G "http://localhost/api/patientprescriptions/1of1oh12g/image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patientprescriptions/1of1oh12g/image"
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
    "message": "No query results for model [App\\Patientprescription] 1of1oh12g"
}
```

### HTTP Request
`GET api/patientprescriptions/{patientprescription}/image`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patientprescription` |  required  | The code of patientprescription.

<!-- END_22740ea801d09a3619a9d30367a57840 -->

<!-- START_78bab5eaed235b36f3d6a6372d8efb33 -->
## _Store Patientprescription_

Patientprescription store endpoint [Must be multipart/form-data request with image file], User must provide prescriptions for registered patients, returns patientprescription instance. !! token required | patient

> Example request:

```bash
curl -X POST \
    "http://localhost/api/patientprescriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"patient_id":15,"prescriptions":"fugit"}'

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
    "patient_id": 15,
    "prescriptions": "fugit"
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
    `patient_id` | integer |  required  | The patient id associated with prescriptions.
        `prescriptions` | image |  required  | The prescriptions image of patient.
    
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
    -d '{"type":12,"amount":5,"status":10}'

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
    "type": 12,
    "amount": 5,
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
    "http://localhost/api/transactions/sequi/status" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"status":12}'

```

```javascript
const url = new URL(
    "http://localhost/api/transactions/sequi/status"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": 12
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
    -G "http://localhost/api/users/rerum/transactions/complete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/rerum/transactions/complete"
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
<!-- START_8653614346cb0e3d444d164579a0a0a2 -->
## _Fetch User_

Get User. !!token_required

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/users/vero" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/users/vero"
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
    "id": 4,
    "mobile": "8801156572072",
    "code": "WjUGehPy9542R2hx",
    "status": 0,
    "is_agent": 0,
    "agent_percentage": 0,
    "balance": 0,
    "device_ids": "[\"fb805ef2-2747-4ea6-bf8c-128a32aa5d40\"]",
    "created_at": "2020-08-06T11:24:40.000000Z",
    "updated_at": "2020-08-06T11:49:32.000000Z",
    "token": "33|xRwEzBwe74QeWiWUoboxgOQtFBsr82qgf6iknRoOxphBX8Pp4PwgAy6nHw1ZGyMcpYPEe62S7VphC3km",
    "roles": [
        {
            "id": 7,
            "name": "doctor",
            "guard_name": "web",
            "created_at": "2020-07-28T19:18:47.000000Z",
            "updated_at": "2020-07-28T19:18:47.000000Z",
            "pivot": {
                "model_id": 4,
                "role_id": 7,
                "model_type": "App\\User"
            }
        }
    ],
    "doctor": {}
}
```

### HTTP Request
`GET api/users/{user}`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `user` |  required  | The user id.

<!-- END_8653614346cb0e3d444d164579a0a0a2 -->

<!-- START_12e37982cc5398c7100e59625ebb5514 -->
## Create/Retrieve User

Get User object using mobile| create new if not present

> Example request:

```bash
curl -X POST \
    "http://localhost/api/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"mobile":"8801955555555","otp_code":"1234","is_patient":true,"device_id":"nam"}'

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
    "mobile": "8801955555555",
    "otp_code": "1234",
    "is_patient": true,
    "device_id": "nam"
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
    "mobile": "8801955555555",
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
        `otp_code` | string |  required  | The 4 digit access otp token sent via sms.
        `is_patient` | boolean |  required  | The boolean representation to indicate if request in from general user. So that if user not found new user will be created.
        `device_id` | string |  optional  | The FCM token of user device.
    
<!-- END_12e37982cc5398c7100e59625ebb5514 -->

<!-- START_c12bac6ca31b0f722944e3067b7de2c2 -->
## Send OTP to user mobile

Get User mobile and send otp.

> Example request:

```bash
curl -X POST \
    "http://localhost/api/users/otp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"mobile":"8801955555555"}'

```

```javascript
const url = new URL(
    "http://localhost/api/users/otp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "mobile": "8801955555555"
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
    "respose": "otp token created"
}
```

### HTTP Request
`POST api/users/otp`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `mobile` | string |  required  | The mobile of the user.
    
<!-- END_c12bac6ca31b0f722944e3067b7de2c2 -->

<!-- START_3155db40d08d4838a8141f823bbe5c22 -->
## _Alter User Agent permission_

Change the user object to modify the is_agent & agent_percentage field. !! token required | super_admin, admin:user

> Example request:

```bash
curl -X PUT \
    "http://localhost/api/users/aperiam/agent" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"is_agent":true,"agent_percentage":2.5}'

```

```javascript
const url = new URL(
    "http://localhost/api/users/aperiam/agent"
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

<!-- START_00ad29745cfa3a140a189cf4f45b667b -->
## _Fetch Pending Checkupprescription By Doctor_

Fetch pending doctor checkupprescriptions. !! token required| super_admin, admin:doctor, doctor

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/doctors/est/checkupprescriptions/pending" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/doctors/est/checkupprescriptions/pending"
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
        "patientcheckup_id": 1,
        "status": 0,
        "code": "ae12e12f12f",
        "contents": null,
        "prescription_path": "",
        "created_at": null,
        "updated_at": null,
        "patientcheckup": {
            "id": 1,
            "patient_id": 1,
            "doctor_id": 2,
            "code": "aabbaaaabb",
            "start_time": "2020-08-16 20:42:05",
            "end_time": null,
            "doctor_rating": 5,
            "patient_rating": 5,
            "created_at": null,
            "updated_at": null,
            "patient": {
                "id": 1,
                "user_id": 5,
                "name": "patient name",
                "code": "aaaaaaaaaa",
                "status": 0,
                "age": "21",
                "gender": 0,
                "address": "asdasdasdasdasdasdasdasd",
                "blood_group": "B -ve",
                "blood_pressure": "100-150",
                "cholesterol_level": "120",
                "height": "5'11''",
                "weight": "90",
                "image": "aaaaaaaaaa_1597428565.png",
                "created_at": "2020-08-09T06:25:34.000000Z",
                "updated_at": "2020-08-14T18:09:26.000000Z"
            }
        }
    }
]
```

### HTTP Request
`GET api/doctors/{doctor}/checkupprescriptions/pending`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `doctor` |  required  | The doctor id associated with the prescriptions.

<!-- END_00ad29745cfa3a140a189cf4f45b667b -->

<!-- START_e349e02a62e75874e94fa58d449141fe -->
## _Fetch Pending Checkupprescription By Patient_

Fetch pending patient checkupprescriptions. !! token required| super_admin, admin:patient, patient

> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/patients/assumenda/checkupprescriptions/pending" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/patients/assumenda/checkupprescriptions/pending"
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
        "patientcheckup_id": 1,
        "status": 0,
        "code": "ae12e12f12f",
        "contents": null,
        "prescription_path": "",
        "created_at": null,
        "updated_at": null,
        "patientcheckup": {
            "id": 1,
            "patient_id": 1,
            "doctor_id": 2,
            "code": "aabbaaaabb",
            "start_time": "2020-08-16 20:42:05",
            "end_time": null,
            "doctor_rating": 5,
            "patient_rating": 5,
            "created_at": null,
            "updated_at": null,
            "patient": {
                "id": 1,
                "user_id": 5,
                "name": "patient name",
                "code": "aaaaaaaaaa",
                "status": 0,
                "age": "21",
                "gender": 0,
                "address": "asdasdasdasdasdasdasdasd",
                "blood_group": "B -ve",
                "blood_pressure": "100-150",
                "cholesterol_level": "120",
                "height": "5'11''",
                "weight": "90",
                "image": "aaaaaaaaaa_1597428565.png",
                "created_at": "2020-08-09T06:25:34.000000Z",
                "updated_at": "2020-08-14T18:09:26.000000Z"
            }
        }
    }
]
```

### HTTP Request
`GET api/patients/{patient}/checkupprescriptions/pending`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `patient` |  required  | The patient id associated with the prescriptions.

<!-- END_e349e02a62e75874e94fa58d449141fe -->

<!-- START_b7e23fffa952c3a35ffbff8dd488d6bb -->
## api/checkupprescriptions/{checkupprescription}/pdf
> Example request:

```bash
curl -X GET \
    -G "http://localhost/api/checkupprescriptions/1/pdf" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions/1/pdf"
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
`GET api/checkupprescriptions/{checkupprescription}/pdf`


<!-- END_b7e23fffa952c3a35ffbff8dd488d6bb -->

<!-- START_1745addaf588c12225ac5744755daf96 -->
## api/checkupprescriptions/{checkupprescription}/pdf
> Example request:

```bash
curl -X PUT \
    "http://localhost/api/checkupprescriptions/1/pdf" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://localhost/api/checkupprescriptions/1/pdf"
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
`PUT api/checkupprescriptions/{checkupprescription}/pdf`


<!-- END_1745addaf588c12225ac5744755daf96 -->


