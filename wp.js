async function getPages(post_type, limit, rest) {
    let result;
    try {
        result = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "json",
            data: {
                action: "list",
                post_type: post_type,
                limit: limit,
                rest: rest
            }
        });
    }
    catch (error) {
        console.error(action, error);
    }
    return result;
}
async function getPageByName(post_type, name) {
    let result;
    try {
        result = await $.ajax({
            url: 'wp.php',
            type: 'POST',
            dataType: "json",
            data: {
                action: "get",
                post_type: post_type,
                name: name
            }
        });
    }
    catch (error) {
        console.error("get", post_type, name, error);
    }
    return result;
}