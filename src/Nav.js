import React, {Component} from 'react'

class Nav extends Component {
    render() {
        return (
            <nav className="breeze">
                <h3><span role="img" aria-label="logo">⛵️</span>Breeze Church Management</h3>
                <ul className="nav-links">
                    <li>People</li>
                    <li>Groups</li>
                </ul>
            </nav>
        );
    }
}

export default Nav;