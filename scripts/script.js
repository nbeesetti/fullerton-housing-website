// Import the 'youtube-transcript' package
const youtubeTranscript = require('youtube-transcript');

async function getCombinedTranscript(videoId) {
    try {
        youtubeTranscript.fetchTranscript(videoId).then(console.log);


        const transcript = await youtubeTranscript.getTranscript(videoId);
        
        let combinedText = "";
        const textLengthToTimeList = [];
        
        let totalTextLength = 0;
        transcript.forEach(entry => {
            const startTimestamp = entry.start;
            const subtitleText = entry.text.toLowerCase();
            
            combinedText += subtitleText + " ";
            
            totalTextLength += subtitleText.length + 1;
            textLengthToTimeList.push({ length: totalTextLength, time: startTimestamp });
        });

        return { combinedText: combinedText.trim(), textLengthToTimeList };
    } catch (e) {
        console.error(`Exception in getCombinedTranscript(), Video: ${videoId}, Error: ${e}`);
        return null;
    }
}

function findPhraseInCombinedText(combinedText, searchPhrase) {
    return combinedText.indexOf(searchPhrase.toLowerCase());
}

function getTimestampFromIndex(textLengthToTimeList, substringIndex) {
    for (const { length, time } of textLengthToTimeList) {
        if (substringIndex < length) {
            return Math.floor(time);
        }
    }
    return null;
}

function generateYouTubeLink(videoId, startTime) {
    return `https://www.youtube.com/watch?v=${videoId}&t=${startTime}s`;
}

async function main(videoId, searchPhrase) {
    const transcript = await getCombinedTranscript(videoId);
    if (transcript) {
        const { combinedText, textLengthToTimeList } = transcript;

        const substringIndex = findPhraseInCombinedText(combinedText, searchPhrase);

        if (substringIndex !== -1) {
            const startTime = getTimestampFromIndex(textLengthToTimeList, substringIndex);
            const link = generateYouTubeLink(videoId, startTime);
            console.log(`YouTube Link: ${link}`);
        } else {
            console.log("Search phrase not found in transcript");
        }
    } else {
        console.log("No transcript");
    }
}

// Example usage
const videoId = 'DktwbunEP8k'; // Replace with actual video ID
const searchPhrase = 'projects in orange county'; // Replace with actual search phrase
main(videoId, searchPhrase);
