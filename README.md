# HNG Backend Task 2
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## User Authentication & Organisation

Using your most comfortable backend framework of your choice, adhere to the following acceptance:
- Connect your application to a Postgres database server. (optional: you can choose to use any ORM of your choice if you want or not).
- Create a User model using the properties below
> NB: user id and email must be unique
```
{
	"userId": "string" // must be unique
	"firstName": "string", // must not be null
	"lastName": "string" // must not be null
	"email": "string" // must be unique and must not be null
	"password": "string" // must not be null
	"phone": "string"
}
```
- Provide validation for all fields. When there’s a validation error, return status code 422 with payload:
```
{
  "errors": [
    {
      "field": "string",
      "message": "string"
    },
  ]
}
```

**Using the schema above, implement user authentication**
*User Registration:*
- Implement an endpoint for user registration
- Hash the user’s password before storing them in the database.
- Successful response: Return the payload with a 201 success status code.

*User Login*
- Implement an endpoint for user Login.
- Use the JWT token returned to access PROTECTED endpoints.

*Organisation*
- A user can belong to one or more organisations
- An organisation can contain one or more users.
- On every registration, an organisation must be created.
- The name property of the organisation takes the user’s firstName and appends “Organisation” to it. For example: user’s first name is John , organisation name becomes "John's Organisation" because firstName = "John" .
- Logged in users can access organisations they belong to and organisations they created.
- Create an organisation model with the properties below.
- Organisation Model:
```
{
	"orgId": "string", // Unique
	"name": "string", // Required and cannot be null
	"description": "string",
}
```
*Endpoints:*
- `[POST] /auth/register` Registers a users and creates a default organisation Register request body:
- `[POST] /auth/login`: logs in a user. When you log in, you can select an organisation to interact with
- `[GET] /api/users/:id`: A user gets their own record or user record in organisations they belong to or created [PROTECTED].
- `[GET] /api/organisations`: gets all your organisations the user belongs to or created. If a user is logged in properly, they can get all their organisations. They should not get another user’s organisation [PROTECTED].
- `[GET] /api/organisations/:orgId`: the logged in user gets a single organisation record [PROTECTED]
Successful response: Return the payload below with a `200` success status code.
- `[POST] /api/organisations`: a user can create their new organisation [PROTECTED].
- `[POST] /api/organisations/:orgId/users`: adds a user to a particular organisation

## Testing
### Unit Testing
Write appropriate unit tests to cover:
1. Token generation - Ensure token expires at the correct time and correct user details is found in token.
1. Organisation - Ensure users can’t see data from organisations they don’t have access to.

### End-to-End Test
**Test Scenarios:**
1. It Should Register User Successfully with Default Organisation:
- Ensure a user is registered successfully when no organisation details are provided.
- Verify the default organisation name is correctly generated (e.g., "John's Organisation" for a user with the first name "John").
- Check that the response contains the expected user details and access token.
1. It Should Log the user in successfully:
- Ensure a user is logged in successfully when a valid credential is provided and fails otherwise.
- Check that the response contains the expected user details and access token.
1. It Should Fail If Required Fields Are Missing:
- Test cases for each required field (firstName, lastName, email, password) missing.
- Verify the response contains a status code of 422 and appropriate error messages.
1. It Should Fail if there’s Duplicate Email or UserID
- Attempt to register two users with the same email.
- Verify the response contains a status code of 422 and appropriate error messages.

## Live Link
The API is hosted on Vercel, base URL is [https://teleport-be.vercel.app](https://teleport-be.vercel.app)