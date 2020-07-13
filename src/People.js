import React, {Component} from 'react'
import BreezeUpload from "./BreezeUpload";
import ResultsList from "./ResultsList";

class People extends Component {
    render() {
        return (
            <div>
                <BreezeUpload/>
                <ResultsList/>
            </div>
        )
    }
}

export default People;