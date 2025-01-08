self.addEventListener('message', function (e) {
    const apiUrl = e.data.apiUrl;
    let lastNewProspectionCount = 0;
    console.log('Worker init');

    // Function to check for new users
    function checkForNewUProspections() {
        console.log('checkForNewUsers init');
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log('Worker resolved data', data);
                if (data !== lastNewProspectionCount) {
                    lastNewProspectionCount = data;
                    self.postMessage(lastNewProspectionCount);
                }
            })
            .catch(error => console.error('Error fetching user data:', error));
    }

    // Check for new users every minute
    setInterval(checkForNewUProspections, 5 * 1000);
});