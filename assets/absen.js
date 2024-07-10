async function isGranted(permissionName) {
    const permission = await navigator.permissions.query(permissionName);
    return permission.state == "granted";
}

function getLocation() {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(({ coords, timestamp }) => resolve({coords, timestamp}), (err) => reject(err));
    });
}

async function getCameraStream() {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    return stream;
}

;(async function() {
    const formData = document.forms[0];
    const canvas = document.createElement('canvas');
    const video = document.createElement('video');
    document.querySelector('main').appendChild(video);

    const takePicture = () => {
        video.pause();
        const ctx = canvas.getContext('2d');
        canvas.width = video.width;
        canvas.height = video.height;
        ctx.fillStyle = "#AAA";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const b64 = canvas.toDataURL('image/png').split(',')[1];
        const blob = new Blob([new Uint8Array([...atob(b64)].map(c => c.charCodeAt(0)))], {type: 'image/png'})
        const file = new File([blob], "photo.png", {lastModified: Date.now(), type: "image/png"});
        const dataTransfer = new DataTransfer()
        dataTransfer.items.add(file);
        
        video.play();
        return dataTransfer.files;
    }

    const getData = async () => {
        const location = await getLocation();
        formData.children.selfie.files = takePicture();
        formData.children.latitude.value = location.coords.latitude;
        formData.children.longitude.value = location.coords.longitude;

        const date = new Date();
        date.setHours(date.getHours() + 7);
        formData.children.waktu.value = date.toISOString().slice(0, 19).replace('T', ' ');
        formData.submit();
    }

    try {
        const videoStream = await getCameraStream();
        const {width, height} = videoStream.getVideoTracks()[0].getSettings();

        video.srcObject = videoStream;
        video.onloadedmetadata = () => video.play();
        video.addEventListener('canplay', () => {
            canvas.width = video.width = width;
            canvas.height = video.height = height;
        })
        video.onclick = () => getData();
    } catch(err) {
        console.log(err);
    }
})();