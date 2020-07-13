import React, {Component} from 'react'
import BreezeUpload from "./BreezeUpload";
import ResultsList from "./ResultsList";
import {Header} from 'semantic-ui-react';

class People extends Component {
    render() {
        return (
            <div>
                <br/>
                <Header as="h3">
                    <span role="img" aria-label="logo">🙋‍♂️🙋‍♀️</span>
                    People CSV
                </Header>
                <BreezeUpload/>
                <ResultsList/>
            </div>
        )
    }
}

export default People;