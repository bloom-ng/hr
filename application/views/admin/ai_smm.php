<style>
    .content-wrapper {
        background-color: #000000 !important;
        color: #ffffff !important;
    }
    .box {
        background-color: #1a1a1a !important;
        color: #ffffff !important;
        border: 1px solid #333333;
    }
    .box-header {
        background-color: #2c2c2c !important;
        color: #ffffff !important;
    }
    .instruction-text {
        font-size: 16px;
        background-color: #2c2c2c !important;
        border-left: 4px solid #007bff;
        padding: 10px;
        margin-bottom: 15px;
        color: #ffffff !important;
    }
    .btn-primary {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: #ffffff !important;
    }
    .btn-primary:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }
    input[type='text'], textarea {
        background-color: #333333 !important;
        color: #ffffff !important;
        border: 1px solid #4d4d4d !important;
    }

    /* Remove these lines:
    @keyframes pulse {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            opacity: 1;
        }
    }

    .animate-pulse {
        animation: pulse 1s infinite ease-in-out;
    }
    */
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            AI-SMM
            <small>Artificial Intelligence Social Media Management</small>
        </h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Generate Social Media Content</h3>
            </div>
            <div class="box-body">
                    <div class="form-group">
                        <label for="ai_input">
                            <strong>Instruction:</strong>
                        </label>
                        <p class="instruction-text">
                            Please provide the brand info, the target audience, the time frame ( month and year), and the social media platform.
                            <br>
                            Sample:
                            <br>
                            Pure Snow Dental Clinic are an accomplished, meticulous, amiable result-oriented dental
surgeons and therapist who offer services in oral medicine, oral surgery,
restorative/conservative dentistry, preventive dentistry, pediatric dentistry, prosthetic dentistry,
orthodontic dentistry, implantology, cosmetic dentistry with dental x-ray facility.
<br>
Target Audience: <br>
1. Families with children seeking high-quality pediatric and general dental care <br>
2. Busy working professionals looking for convenient evening and
weekend appointments <br>
3. Senior citizens requiring specialized care like dentures, implants, etc. <br>
4. Individuals interested in cosmetic dentistry services to improve their smiles <br>
5. Active adults and athletes needing preventative care like mouthguards <br>
Time frame: September 2024 
                        </p>
                        <textarea class="form-control" id="ai_input" name="ai_input" rows="5" required></textarea>
                    </div>
                    <button id="generateButton" type="button" class="btn btn-primary">Generate</button>
            </div>
            <div id="loadingIndicator" class="box-body hidden">
                <p class="animate-pulse">Generating content, please wait...</p>
            </div>
            <div id="resultTable" class="box-body" style="display: none;">
                <h3 class="box-title">Generated Content</h3>
                <button id="printButton" type="button" class="btn btn-primary" style="margin-bottom: 10px;">Print Table</button>
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Caption</th>
                            <th>Content</th>
                        </tr>
                    </thead>
                    <tbody id="resultTableBody">
                    </tbody>
                </table>
                
            </div>
        </div>

        
    </section>
</div>


<script>
    
    let messageInput = document.getElementById("ai_input");
    let resultTable = document.getElementById("resultTable");
    let resultTableBody = document.getElementById("resultTableBody");
    let loadingIndicator = document.getElementById("loadingIndicator");
    let generateButton = document.getElementById("generateButton");

    generateButton.addEventListener("click", async (e)=> {
        loadingIndicator.classList.remove('hidden');
        loadingIndicator.classList.add('block');
        message = messageInput.value
        console.log(message) 
        const myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");

        const raw = JSON.stringify({
         "message": message
        });

        const requestOptions = {
            method: "POST",
            headers: myHeaders,
            body: raw,
            redirect: "follow"
        };

        try {
            let res = await fetch("https://aismm.bloomdigitmedia.com/predict", requestOptions)
            res = await res.json()
            console.log("res", res)

            // Parse the response and create table rows
            const parsedData = JSON.parse(res.response);
            resultTableBody.innerHTML = '';
            parsedData.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.DATE}</td>
                    <td>${item.CAPTION}</td>
                    <td>${item.CONTENT}</td>
                `;
                resultTableBody.appendChild(row);
            });

            // Hide loading indicator, enable button, and show the result table
            loadingIndicator.classList.remove('block');
            loadingIndicator.classList.add('hidden');
            generateButton.disabled = false;
            resultTable.style.display = 'block';
        } catch (error) {
            console.log(error)
            alert('An error occurred while generating content. Please try again.');
            
            // Hide loading indicator and enable button on error
            loadingIndicator.classList.remove('block');
            loadingIndicator.classList.add('hidden');
            generateButton.disabled = false;
        }

    });

    document.getElementById("printButton").addEventListener("click", () => {
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>AI-SMM Generated Content</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('table { border-collapse: collapse; width: 100%; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('th { background-color: #f2f2f2; }');
        printWindow.document.write(' #printButton{ display: none; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1>AI-SMM Generated Content</h1>');
        printWindow.document.write(document.getElementById("resultTable").innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>