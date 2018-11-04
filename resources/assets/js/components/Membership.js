import React, {Component} from "react";
import {get, post} from "./Api";
import { NavLink } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {faCheckCircle, faClock, faSpinner} from "@fortawesome/free-solid-svg-icons/index";
import { Loading } from './Utilities';

class Membership extends Component
{
    constructor(props) {
        super(props);
        this.state = {
            membership: null,
            user: null,
            membershipLoaded: false,
            userLoaded: false
        }
        this.register = this.register.bind(this);
    }

    componentDidMount() {
        get('/api/users/current').then(user => {
            this.setState({ user });
            this.setState({ userLoaded: true });
        });
        get('/api/membership/current').then(membership => {
            this.setState({ membership });
            this.setState({ membershipLoaded: true });
        }).catch(() => {
            this.setState({ 'membership': null });
            this.setState({ membershipLoaded: true });
        });
    }

    register() {
        post('/api/membership', { userId: this.state.user.id })
            .then(membership => this.setState({ membership }));
    }

    render() {
        if (!this.state.membershipLoaded || !this.state.userLoaded) {
            return <Loading />;
        }

        const infoCompleted = this.state.user != null &&
            this.state.user.gender != null &&
            this.state.user.birthDate != null;

        let step = 0;
        if (infoCompleted) {
            step = 1;
            if (this.state.membership != null) {
                step = 2;
                if (this.state.membership.paid != null) {
                    step = 3;
                }
            }
        }
        const successClass = "card text-white bg-success mb-2";
        const defaultClass = "card mb-2";
        return (
            <div>
                <h1>Membership</h1>
                <div className={step >= 1 ? successClass : defaultClass}>
                    <div className="card-body">
                        <h5 className="card-title">1. Fill out your information</h5>
                        {step >= 1 && <p>
                            <FontAwesomeIcon icon={faCheckCircle} size="lg"/>
                            <span> Information OK</span>
                        </p>}
                        {step === 0 &&
                            <React.Fragment>
                                <p>We need a few details about you to register you as a member.</p>
                                <NavLink to="/profile" className="btn btn-primary">Fill out your information now</NavLink>
                            </React.Fragment>}
                    </div>
                </div>
                <div className={step >= 2 ? successClass : defaultClass}>
                    <div className="card-body">
                        <h5 className="card-title">2. Register/renew your membership</h5>
                        {step >= 2 && <p>
                            <FontAwesomeIcon icon={faCheckCircle} size="lg"/>
                            <span> Registration received</span>
                        </p>}
                        {step === 1 &&
                            <button type="button" className="btn btn-primary" onClick={this.register}>Register now</button>}
                    </div>
                </div>
                <div className={step >= 3 ? successClass : defaultClass}>
                    <div className="card-body">
                        <h5 className="card-title">3. Pay your membership fee</h5>
                        {step >= 3 && <p>
                            <FontAwesomeIcon icon={faCheckCircle} size="lg"/>
                            <span> Registration completed!</span>
                        </p>}
                        {step === 2 && <p>
                            <FontAwesomeIcon icon={faClock} size="lg"/>
                            <span> Waiting for payment</span>
                        </p>}
                    </div>
                </div>
            </div>
        )
    }
}

export default Membership;