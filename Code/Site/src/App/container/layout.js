import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Actions as AuthAction } from '../redux/action/auth';
import { Link, withRouter } from "react-router-dom";
import IziToast from '../component/client/izi-toast';
import logo from '../assets/logo.jpg';

import "./layout.css";

import {
    Col,
    Container,
    Form,
    Nav,
    Navbar,
    OverlayTrigger,
    Popover,
    Row
} from 'react-bootstrap';

import iziModal from 'izimodal/js/iziModal.min.js';
import 'izimodal/css/iziModal.min.css';

import '@tabler/core/dist/css/tabler.min.css';
import '@tabler/core/dist/js/tabler.min.js';

import { IconHome, IconUsers, IconPower } from '@tabler/icons'; 

import $ from 'jquery';
$.fn.iziModal = iziModal;


class LayoutComponent extends Component {

    constructor(props, context) {
        super(props)
        this.context = context;

        this.navActive = '/';
    }

    render() {
        return (
            <div className="wrapper">
                <Navbar as="header" expand="md" variant="light" className="d-print-none">
                    <Container fluid>
                        <Navbar.Brand as="h1" className="navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                            <a href="/">
                                <img
                                    src={logo}
                                    alt="Logo"
                                    className="navbar-brand-image"
                                />
                            </a>
                        </Navbar.Brand>
                        <Navbar.Toggle as="button" data-bs-toggle="collapse" data-bs-target="#responsive-navbar-nav" />
                        <Navbar.Collapse id="responsive-navbar-nav">
                            <div className="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                                <Nav as="ul">
                                    <Nav.Item as="li" className={this.props.location.pathname === "/" ? "active" : ""}>
                                        <Link to="/" className="nav-link">
                                            <span className="nav-link-icon d-md-none d-lg-inline-block">
                                                <IconHome />
                                            </span>
                                            <span className="nav-link-title">
                                                Home
                                            </span>
                                        </Link>
                                    </Nav.Item>
                                </Nav>
                            </div>
                        </Navbar.Collapse>
                    </Container>
                </Navbar>
                <div className="page-wrapper">
                    <div className="page-body">
                        <Container className="d-flex justify-content-center">
                            {this.props.children}  
                        </Container>
                    </div>
                    <footer className="footer footer-transparent d-print-none">
                        <Container fluid className="text-center">
                            Copyright &copy; { (new Date()).getFullYear() }&nbsp;-&nbsp;
                            Ficticious Drinking Site For Caffeine Lovers
                        </Container>
                    </footer>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        username: state.auth.get('username')
    }
}

const mapDispatchToProps = (dispatch) => {
    return {
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(LayoutComponent))
