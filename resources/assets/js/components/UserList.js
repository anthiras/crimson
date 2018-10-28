import React, { Component } from 'react';
import {get} from "./Api";
import UserRow from "./UserRow";

class UserList extends Component
{
    constructor(props) {
        super(props);
        this.state = {
            roles: [],
            users: [],
        }
    }

    componentDidMount() {
        get('/api/roles').then(roles => {
            this.setState({ roles })
        });
        get('/api/users?include[]=roles&include[]=memberships').then(users => {
            this.setState({ users });
        });
    }

    render() {
        return (
            <table className="table">
                <thead>
                    <tr>
                        <th width="50"></th>
                        <th>Name</th>
                        <th>Roles</th>
                        <th>Membership</th>
                    </tr>
                </thead>
                <tbody>
                    {this.state.users.map(user => {
                        return (
                            <UserRow key={user.id} user={user} allRoles={this.state.roles} />
                        );
                    })}
                </tbody>
            </table>
        );
    }
}

export default UserList;