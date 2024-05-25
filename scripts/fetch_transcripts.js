
// directions:
// cd into scripts
// node fetch_transcripts.js
// saves json in scripts/transcripts dir

// const fs = require('fs');
// const { YoutubeTranscript } = require('youtube-transcript');

// async function fetchAndSaveTranscript(videoId) {
//     // YoutubeTranscript.fetchTranscript('DktwbunEP8k').then(console.log);
//     try {
//         const transcript = await YoutubeTranscript.fetchTranscript(videoId);
//         fs.writeFileSync(`transcripts/${videoId}.json`, JSON.stringify(transcript));
//         console.log(`Transcript for video ${videoId} saved successfully.`);
//     } catch (error) {
//         console.error(`Error fetching transcript for video ${videoId}:`, error);
//     }
// }

// const videoIds = ['DktwbunEP8k'];
// videoIds.forEach(fetchAndSaveTranscript);


const fs = require('fs');
const { YoutubeTranscript } = require('youtube-transcript');

async function fetchAndSaveTranscript(videoId) {
    try {
        const transcript = await YoutubeTranscript.fetchTranscript('DktwbunEP8k');
        
        let combinedText = "";
        const textLengthToTimeList = [];

        let totalTextLength = 0;
        transcript.forEach(entry => {
            const startTimestamp = entry.offset;
            const subtitleText = entry.text.toLowerCase();

            combinedText += subtitleText + " ";

            totalTextLength += subtitleText.length + 1;
            textLengthToTimeList.push({ textLength: totalTextLength, timestamp: startTimestamp });
        });

        const transcriptData = {
            videoId,
            combinedText: combinedText.trim(),
            textLengthToTimeList
        };

        fs.writeFileSync(`transcripts/${videoId}.json`, JSON.stringify(transcriptData));
        console.log(`Transcript for video ${videoId} saved successfully`);
    } catch (error) {
        console.error(`Error fetching transcript for video ${videoId}:`, error);
    }
}

const videoIds = ['DktwbunEP8k'];
videoIds.forEach(fetchAndSaveTranscript);

