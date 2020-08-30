const functions = require('firebase-functions');

// // Create and Deploy Your First Cloud Functions
// // https://firebase.google.com/docs/functions/write-firebase-functions
//
// exports.helloWorld = functions.https.onRequest((request, response) => {
//   functions.logger.info("Hello logs!", {structuredData: true});
//   response.send("Hello from Firebase!");
// });
exports.myFunction = functions.firestore
    .document('doctorcall/{doc_id}')
    .onWrite((change, context) => {
        const newValue = snap.data();
        const deviceIds = newValue.receiving_device;

        const fcmMessage = {
            'registration_ids': deviceIds,
            'notification': {
                'body': '',
                'title': ''
            },
            'data': {}
        }
    });
