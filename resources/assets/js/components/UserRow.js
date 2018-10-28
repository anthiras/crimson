import React, {Component} from 'react';
import Auth from "./Auth";
import UserRoleCheckbox from './UserRoleCheckbox';
import { post } from './Api';

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
            })),
            membership: this.user.currentMembership
        };

        this.setMembershipPaid = this.setMembershipPaid.bind(this);
    }

    setMembershipPaid() {
        post('/api/membership/'+this.user.id+'/setPaid')
            .then(membership => this.setState({ membership }));
    }

    render() {
        const membership = this.state.membership;
        const memberNotPaid = membership != null && membership.paidAt == null;
        const memberPaid = membership != null && membership.paidAt != null;
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
                <td>
                    {memberNotPaid && <button type="button" className="btn btn-primary" onClick={this.setMembershipPaid}>Confirm payment</button> }
                    {memberPaid && "Confirmed paid member"}
                </td>
            </tr>
        );
    }
}

export default UserRow;