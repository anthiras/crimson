import React, { Component } from 'react';
import {get} from "./Api";
import UserRow from "./UserRow";
import { Loading } from './Utilities';

class UserList extends Component
{
    constructor(props) {
        super(props);
        this.state = {
            roles: [],
            users: [],
            loading: false
        }
    }

    componentDidMount() {
        this.setState({ loading: true });
        get('/api/roles').then(roles => {
            this.setState({ roles })
        });
        get('/api/users?include[]=roles&include[]=memberships').then(users => {
            this.setState({ users, loading: false });
        });
    }

    render() {
        if (this.state.loading) return <Loading />;
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
                    {this.state.users.map(user =>
                        <UserRow key={user.id} user={user} allRoles={this.state.roles} />
                    )}
                </tbody>
            </table>
        );
    }
}

export default UserList;