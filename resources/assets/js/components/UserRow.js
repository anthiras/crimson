import React, {Component} from 'react';
import Auth from "./Auth";
import UserRoleCheckbox from './UserRoleCheckbox';

class UserRow extends Component
{
    constructor(props) {
        super(props);
        this.auth = new Auth();
        this.user = this.props.user;
        this.allRoles = this.props.allRoles;
        this.state = {
            roles: this.allRoles.map(role => ({
                userId: this.user.id,
                roleId: role.id,
                name: role.name,
                userHasRole: this.user.roles.find((r) => { return r.id == role.id; }) !== undefined
            }))
        };
    }

    render() {
        return (
            <tr>
                <td><img src={this.user.picture} width="50" height="50" /></td>
                <td>{this.user.name}</td>
                <td>
                    {this.state.roles.map(userRole => {
                        return (
                            <UserRoleCheckbox key={userRole.roleId} userRole={userRole} />
                        );
                    })}
                </td>
            </tr>
        );
    }
}

export default UserRow;