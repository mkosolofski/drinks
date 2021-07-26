import React, { Component } from 'react';
import { connect } from 'react-redux'
import * as ApiClient from '../../component/drinks-api/client';
import IziToast from '../../component/client/izi-toast';
import { IconThumbUp, IconUser, IconCircle1, IconCircle2 } from '@tabler/icons'; 
import {
    Button,
    Card,
    Form
} from 'react-bootstrap';
import '@tabler/core/dist/css/tabler.min.css';
import '@tabler/core/dist/js/tabler.min.js';
import "./home.css"

class Home extends Component {
    constructor(props, context) {
        super(props)
        this.context = context;

        this.state = {
            drinks: [],
            users: []
        };

        this.newName = React.createRef();
        this.userSelectRef = React.createRef();
        this.drinkSelectRef = React.createRef();
        this.consumeDrinkButtonRef = React.createRef();
        
        this.existingUserInfoContainerRef = React.createRef();
        this.drinkInfoContainerRef = React.createRef();

        this.newUserRadioRef = React.createRef();
        this.existingUserRadioRef = React.createRef();
    }
    
    componentDidMount() {
        this.loadDrinks();
        this.loadUsers();
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if (
            prevState.users.length !== this.state.users.length
            && this.state.users.length > 0
        ) {
            this.onUserSelect();
        }

        if (
            prevState.drinks.length !== this.state.drinks.length
            && this.state.drinks.length > 0
        ) {
            this.onDrinkSelect();
        }
    }

    loadUsers() {
        IziToast.success(
            '<div class="d-flex align-items-center mb-2"><span>Loading Users...</span><span class="mx-1"></span><div class="spinner-border spinner-border-sm" role="status"></div></div>',
            '',
                {
                position: 'center',
                overlay: true,
                timeout: 50000,
                progressBar: true,
                close: false,
                icon: 'fa fa-users',
                onOpened: (props, toast) => {
                    ApiClient.getUsers()
                        .then(
                            (response) => {
                                response.json()
                                    .then(
                                        (response) => {
                                            this.setState({users: response});
                                            IziToast.hide(toast);

                                        }
                                    );
                            }
                        );
                }
            }
        );
    }

    loadDrinks(onComplete) {
        IziToast.success(
            '<div class="d-flex align-items-center mb-2"><span>Loading Drinks...</span><span class="mx-1"></span><div class="spinner-border spinner-border-sm" role="status"></div></div>',
            '',
                {
                position: 'center',
                overlay: true,
                timeout: 50000,
                progressBar: true,
                close: false,
                icon: 'fa fa-coffee',
                onOpened: (props, toast) => {
                    ApiClient.getDrinks()
                        .then(
                            (response) => {
                                response.json()
                                    .then(
                                        (response) => {
                                            this.setState({drinks: response});
                                            IziToast.hide(toast);
                                        }
                                    );
                            }
                        );
                }
            }
        );
    }

    getUser(userId, callbackComplete) {
        ApiClient.getUser(userId)
            .then(
                (response) => callbackComplete(response)
            );
    }


    onUserSelect() {
        IziToast.success(
            '<div class="d-flex align-items-center mb-2"><span>Getting User Info</span><span class="mx-1"></span><div class="spinner-border spinner-border-sm" role="status"></div></div>',
            '',
                {
                position: 'center',
                overlay: true,
                timeout: 50000,
                progressBar: true,
                close: false,
                icon: 'fa fa-users',
                onOpened: (props, toast) => {
                    this.getUser(
                        this.userSelectRef.current.value,
                        (response) => {
                            response.json()
                                .then(
                                    (response) => {
                                        
                                        let totalConsumedCaffeine = 0,
                                            consumedTableRows = '';

                                        response.user_drinks.forEach(
                                            (userDrink) => {
                                                totalConsumedCaffeine += userDrink.drink.caffeine;

                                                consumedTableRows += `<tr>
                                                    <td>` + userDrink.drink.name + `</td>
                                                    <td>` + userDrink.drink.caffeine + `</td>
                                                </tr>`;
                                            
                                            }
                                        );

                                        this.existingUserInfoContainerRef.current.innerHTML = `
                                            <table class="existing-user-info-table table table-hover mt-3">
                                                <thead>
                                                    <tr>
                                                        <th class="text-nowrap">Drink Name</th>
                                                        <th class="text-nowrap">Drink Caffeine (mg)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ` + consumedTableRows + `
                                                    <tr>
                                                        <td colspan="2">
                                                            Total Consumed Caffeine
                                                            <span class="text-info font-weight-bold">` + totalConsumedCaffeine + `mg</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        `;
                                        IziToast.hide(toast);
                                    }
                                );
                        }
                    )
                }
            }
        );
    }

    onDrinkSelect() {
        let drinkTableRow = '';
        
        this.state.drinks.forEach(
            (drink) => {
                if (drink.id != this.drinkSelectRef.current.value) return;

                drinkTableRow = `<tr>
                    <td>` + drink.caffeine + `</td>
                    <td>` + drink.description + `</td>
                </tr>`;
            
            }
        );

        this.drinkInfoContainerRef.current.innerHTML = `
            <table class="drink-info-table table table-hover mt-3">
                <thead>
                    <tr>
                        <th class="text-nowrap">Drink Caffeine (mg)</th>
                        <th class="text-nowrap">Drink Description</th>
                    </tr>
                </thead>
                <tbody>
                    ` + drinkTableRow + `
                </tbody>
            </table>
        `;
    }

    displayWarningNotification(message) {
        IziToast.warning(
            message,
            "",
            {
                position: 'center',
                overlay: true,
                timeout: 2000,
                icon: 'fa fa-exclamation-triangle',
                close: true
            }
        );
    }

    displayErrorNotification(message) {
        IziToast.error(
            "",
            message,
            {
                position: 'center',
                overlay: true,
                timeout: 0,
                icon: 'fa fa-exclamation-triangle',
                close: false,
                buttons: [
                    [
                        '<span><button class="btn btn-danger btn-sm confirm-reset-ok-button">Continue</button></span>',
                        (instance, toast) => {
                            instance.hide({transitionOut: 'fadeOutUp'}, toast);
                            this.setState({uploadedFile:null});
                        },
                        true
                    ]
                ]
            }
        );
    }

    createUser() {

        const userName = this.newName.current.value.trim();
        if (userName === '') {
            this.displayWarningNotification('Oops!!! Please specify a name.');
            return;
        }

        IziToast.success(
            '<div class="d-flex align-items-center mb-2"><span>Consuming drink!</span><span class="mx-1"></span><div class="spinner-border spinner-border-sm" role="status"></div></div>',
            '',
            {
                position: 'center',
                overlay: true,
                timeout: 50000,
                progressBar: true,
                close: false,
                icon: 'fa fa-coffee',
                onOpened: (props, toast) => {
                        ApiClient.createUser(
                            this.newName.current.value,
                            this.drinkSelectRef.current.value
                        ).then(
                            (response) => {

                                response.json()
                                    .then(
                                        (responseBody) => {
                                            const userId = responseBody.hasOwnProperty("id") ? responseBody.id : null;
                                            const userName = responseBody.hasOwnProperty("name") ? responseBody.name : null;

                                            (response.status === 400 && userName) ?
                                                this.displayErrorNotification(userName + '. Please select an existing name or try a different name.') :
                                                this.loadUsers();

                                            IziToast.hide(toast);
                                        }
                                    );
                            }
                        );
                }
            }
        );
    }

    updateUser() {
        IziToast.success(
            '<div class="d-flex align-items-center mb-2"><span>Consuming drink!</span><span class="mx-1"></span><div class="spinner-border spinner-border-sm" role="status"></div></div>',
            '',
            {
                position: 'center',
                overlay: true,
                timeout: 50000,
                progressBar: true,
                close: false,
                icon: 'fa fa-coffee',
                onOpened: (props, toast) => {
                        ApiClient.addUserDrink(
                            this.userSelectRef.current.value,
                            this.drinkSelectRef.current.value
                        ).then(
                            (response) => {

                                response.json()
                                    .then(
                                        (responseBody) => {
                                            (response.status === 403) ?
                                                this.displayErrorNotification(responseBody.message) :
                                                this.onUserSelect(); // Force refesh of existing user info.

                                            IziToast.hide(toast);
                                        }
                                    );
                            }
                        );
                }
            }
        );

    }

    onConsumeDrinkClick() {
        this.newUserRadioRef.current.checked ?
            this.createUser() :
            this.updateUser();
    }

    onUserTypeFocus(type) {
        type === "newUser" ? this.newUserRadioRef.current.click() : this.existingUserRadioRef.current.click()
    }

    render() {

        return (
            <Card className="page">
                <Card.Body>

                    <Card className="mb-3 card-step">
                        <Card.Header className="font-weight-bold text-white bg-info text-center">
                            <IconCircle1 size="30"/><span className="mx-1">Who Wants Caffeine?</span>
                        </Card.Header>
                        <Card.Body>
                            <div>
                                <div className="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                    <label className="form-selectgroup-item flex-fill" >
                                        <input
                                            type="radio"
                                            name="userTypeRadio"
                                            defaultValue="visa"
                                            className="form-selectgroup-input"
                                            ref={this.newUserRadioRef}
                                            defaultChecked
                                        />
                                        <div className="form-selectgroup-label d-flex align-items-center p-3">
                                            <div className="me-3">
                                                <span className="form-selectgroup-check" />
                                            </div>
                                            <div>
                                                New User
                                                <div className="input-icon w-100">
                                                    <span className="input-icon-addon">
                                                        <IconUser />
                                                    </span>
                                                    <input
                                                        type="text"
                                                        className="form-control"
                                                        placeholder="Enter Name"
                                                        onFocus={() => this.onUserTypeFocus("newUser")}
                                                        ref={this.newName}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <label className="form-selectgroup-item flex-fill">
                                        <input
                                            type="radio"
                                            name="userTypeRadio"
                                            defaultValue="mastercard"
                                            className="form-selectgroup-input" 
                                            ref={this.existingUserRadioRef}
                                            disabled={ this.state.users.length > 0 ? "" : "disabled" }
                                        />
                                        <div className="form-selectgroup-label d-flex align-items-center p-3">
                                            <div className="me-3">
                                                <span className="form-selectgroup-check" />
                                            </div>
                                            <div className="w-100">
                                                Existing User
                                                <Form.Control
                                                    as="select"
                                                    className="form-select"
                                                    onChange={() => this.state.users.length > 0 ? this.onUserSelect() : ''}
                                                    onFocus={() => this.onUserTypeFocus("existingUser")}
                                                    ref={this.userSelectRef}
                                                    disabled={ this.state.users.length > 0 ? "" : "disabled" }
                                                >
                                                    {
                                                        this.state.users.map(
                                                            (user, index) => {
                                                                return <option value={user.id} >{user.name}</option>;
                                                            }
                                                        )
                                                    }
                                                </Form.Control>
                                                <div ref={this.existingUserInfoContainerRef}></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </Card.Body>
                    </Card>

                    <Card className="mb-2 card-step">
                        <Card.Header className="font-weight-bold text-white bg-info text-center">
                            <IconCircle2 size="30"/><span className="mx-1">What Should Be Consumed?</span>
                        </Card.Header>
                        <Card.Body>
                            <Form.Control
                                as="select"
                                className="form-select"
                                onChange={(event) => this.onDrinkSelect()}
                                ref={this.drinkSelectRef}
                            >
                                {
                                    this.state.drinks.map(
                                    (drink, index) => {
                                        return <option value={drink.id} >{drink.name}</option>;
                                    }
                                    )
                                }
                            </Form.Control>
                            <div ref={this.drinkInfoContainerRef}></div>
                        </Card.Body>
                    </Card>

                    <div className="text-center">
                        <Button
                            size="lg"
                            variant="outline-success"
                            onClick={() => this.onConsumeDrinkClick()}
                            ref={this.consumeDrinkButtonRef}
                        >
                            <IconThumbUp/>
                            Consume Drink! I Need My Caffeine Fix!
                        </Button>
                    </div>
                </Card.Body>
            </Card>
       );
    }
}

export default Home
