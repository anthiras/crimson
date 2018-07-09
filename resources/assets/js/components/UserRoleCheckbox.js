import React, {Component} from 'react';
import {del, post} from "./Api";

class UserRoleCheckbox extends Component {
    constructor(props) {
        super(props);
        this.name = props.userRole.name;
        this.userId = props.userRole.userId;
        this.roleId = props.userRole.roleId;
        this.checkboxId = this.userId + '_' + this.roleId;
        this.state = {
            userHasRole: props.userRole.userHasRole
        };
        this.toggleRole = this.toggleRole.bind(this);
    }

    toggleRole() {
        let url = 'api/users/' + this.userId + '/roles/' + this.roleId;
        if (this.state.userHasRole) {
            del(url).then(() => {
                this.setState({userHasRole: false});
            });
        } else {
            post(url).then(() => {
                this.setState({userHasRole: true});
            });
        }
    }

    render() {
        return (
            <div className="form-check">
                <input className="form-check-input" type="checkbox" id={this.checkboxId}
                       checked={this.state.userHasRole} onChange={this.toggleRole}/>
                <label className="form-check-label" htmlFor={this.checkboxId}>{this.name}</label>
            </div>
        )
    }
}

export default UserRoleCheckbox;