import React, {Component} from 'react';

class Avatar extends Component {
    render() {
        return (
            <div className="relative flex justify-center w-full m-auto">
                <div className="absolute hidden -translate-50 self-center pin-t p-4 justify-center items-center bg-white rounded-full shadow sm:flex">
                    <img className="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
                </div>
            </div>
        );
    }
}

export default Avatar;