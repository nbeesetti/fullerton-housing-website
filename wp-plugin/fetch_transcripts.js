
// directions:
// cd wp-plugin
// node fetch_transcripts.js
// saves json in scripts/transcripts dir

const fs = require('fs');
const { YoutubeTranscript } = require('youtube-transcript');

async function fetchAndSaveTranscript(videoId) {
    try {
        const transcript = await YoutubeTranscript.fetchTranscript(videoId);
        
        let combinedText = "";
        const prefixSumTextLengths = [];
        const timestamps = [];

        let totalTextLength = 0;
        transcript.forEach(entry => {
            const startTimestamp = entry.offset;
            const subtitleText = entry.text.toLowerCase();

            combinedText += subtitleText + " ";

            totalTextLength += subtitleText.length + 1;
            prefixSumTextLengths.push(totalTextLength);
            timestamps.push(startTimestamp);
        });

        const transcriptData = {
            videoId: videoId,
            combinedText: combinedText.trim(),
            prefixSumTextLengths: prefixSumTextLengths,
            timestamps: timestamps
        };

        fs.writeFileSync(`transcripts/${videoId}.json`, JSON.stringify(transcriptData));
        console.log(`Transcript for video ${videoId} saved successfully`);
    } catch (error) {
        console.error(`Error fetching transcript for video ${videoId}:`, error);
    }
}

const videoIds = ['TRqlaM5IlMw', 'GntTXqYXcfQ', 'pwknjorNBJw', 'tfp7BnHc7Ec'];
videoIds.forEach(fetchAndSaveTranscript);

