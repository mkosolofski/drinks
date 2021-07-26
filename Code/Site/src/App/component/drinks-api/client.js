export function getDrinks() {
    return fetch(
        process.env.REACT_APP_DRINKS_API_URL + "/api/drinks",
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        }
    );
}

export function addUserDrink(userId, drinkId) {

    return fetch(
        process.env.REACT_APP_DRINKS_API_URL + "/api/user/drinks",
        {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(
                {
                    id: userId,
                    user_drinks: [
                        {
                            drink: {
                                id: drinkId
                            }
                        }
                    ]
                }
            
            )
        }
    );
}

export function getUsers() {
    return fetch(
        process.env.REACT_APP_DRINKS_API_URL + "/api/users",
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
            }
        }
    );
}


export function getUser(userId) {
    return fetch(
        process.env.REACT_APP_DRINKS_API_URL + "/api/user/" + userId,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
            }
        }
    );
}

export function createUser(name, drinkId) {

    return fetch(
        process.env.REACT_APP_DRINKS_API_URL + "/api/user",
        {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify(
                {
                    name: name,
                    user_drinks: [
                        {
                            drink: {
                                id: drinkId
                            }
                        }
                    ]
                }
            )
        }
    );
}
