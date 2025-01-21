
export const removeLastPathSegment = (path_str) => {
    let path_segments = path_str.split("/");

    if(path_segments[path_segments.length-1] === "") {
        path_segments.pop();
        path_segments.pop();
    } else {
        path_segments.pop();
    }

    return path_segments.join("/");
}
