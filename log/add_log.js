function log(message) {
    const params = new URLSearchParams();   
    params.add(message);
    
    axios.post("http://geyamaclub.s203.xrea.com/Setting/add_log.php", params);
}