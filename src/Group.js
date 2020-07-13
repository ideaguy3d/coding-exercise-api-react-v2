import React, {Component} from 'react';
import BreezeUpload from "./BreezeUpload";
import GroupsList from "./GroupsList";
import {Header} from 'semantic-ui-react';

class Group extends Component {
    render() {
        return (
            <div>
                <br/>
                <Header as="h3">
                    <span role="img" aria-label="logo">👩‍🦳👴👱‍♀️👱‍♂️🧔</span>
                    Groups CSV
                </Header>
                <BreezeUpload/>
                <GroupsList/>
            </div>
        );
    }
}

export default Group;