export default function(Vue) {
    Vue.auth = {
        //Set token
        setDetails(email, name, image, permission) {
            localStorage.setItem(
                '_a',
                JSON.stringify({
                    email: email,
                    name: name,
                    image: image,
                    permission: permission
                })
            );
        },

        //Get token and check it
        getUserInfo(request) {
            const data = JSON.parse(localStorage.getItem('_a'));
            if (data !== null) {
                if (request === 'permission') {
                    return data.permission;
                } else if (request === 'name') {
                    return data.name;
                } else if (request === 'image') {
                    return data.image;
                } else if (request === 'email') {
                    return data.email;
                }
            } else {
                return false;
            }
        },

        // Set image and username
        setUpdate(name, image, email) {
            const data = JSON.parse(localStorage.getItem('_a'));
            if (name !== null) {
                data.name = name;
            }
            if (image !== null) {
                data.image = image;
            }
            if (email !== null) {
                data.language = email;
            }
            localStorage.setItem('_a', JSON.stringify(data)); //put the object back
        }
    };
    //make auth global
    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: () => {
                return Vue.auth;
            }
        }
    });
}
