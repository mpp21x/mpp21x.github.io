---
title: LeetCode(1282) - Group the People Given the Group Size They Belong To (js/medium)
date: 2020-05-10 14:37:44
tags:
- leetcode
- javascript
- algorithm
img: /medias/page_images/leetcode-1282-group-the-People-Given-the-Group-Size-They-Belong-To.jpeg
categories: leetcode algorithms
---
* link：[Group the People Given the Group Size They Belong To](https://leetcode.com/problems/group-the-people-given-the-group-size-they-belong-to/)
* difficulty：`medium`
* language：`javascript`

## Question

There are n people whose IDs go from 0 to n - 1 and each person belongs exactly to one group. Given the array groupSizes of length n telling the group size each person belongs to, return the groups there are and the people's IDs each group includes.

You can return any solution in any order and the same applies for IDs. Also, it is guaranteed that there exists at least one solution. 

Example 1:

```
Input: groupSizes = [3,3,3,3,3,1,3]
Output: [[5],[0,1,2],[3,4,6]]
Explanation: 
Other possible solutions are [[2,1,6],[5],[0,4,3]] and [[5],[0,6,2],[4,3,1]].
```

Example 2:

```
Input: groupSizes = [2,1,3,3,3,2]
Output: [[1],[0,5],[2,3,4]]
```

Constraints:

* `groupSizes.length == n`
* `1 <= n <= 500`
* `1 <= groupSizes[i] <= n`

### Question Explanation

給予一個充滿整數的陣列 `groupSizes`，這個陣列每一個整數都代表一個人其所屬的群組人數，而我們要做的便是給予每一個人 ID (從 0 到 `n-1`)，以及按任何順序歸類在同一個群組。

總之，最後要回傳一個二維陣列，陣列中的每一個陣列元素代表其群組，裡面必須包含每個人的 ID。


## 解題思路

我們要做的有以下幾個步驟（數字為代稱，不一定為實際順序）：

 1. **遍歷取出數字**： `for` 遍歷 `groupSizes`，取出 `groupSize` 
 2. **將 ID 放至群組**：將 For 迴圈的 `index` 作為每個人的 ID，放到各自所屬的一個變數 `tempGroup`
 3. **暫存尚未湊滿 groupSize 的 group**：在上述的步驟 2 當中，變數 `tempGroup` 若尚未湊滿其應有的 `groupSize`，便不應該將其放至 `ans`，這時可以先準備一個 `tempGroups` 暫存這些 `tempGroup`，之後每次新增人數時以及將 `tempGroup` 放入 `ans` ，也優先從這裡取出。
 4. **將群組放在最後欲回傳的變數**：確認 `tempGroup` 人數是否湊滿，湊滿的話，便將其集合到一個陣列變數 `ans` 最後將此二維陣列回傳即可。

## How to implement algorithm in javascript

```javascript
/**
 * @param {number[]} groupSizes
 * @return {number[][]}
 */
var groupThePeople = function (groupSizes) {

    const sizesLength = groupSizes.length;
    const tempGroups = {};
    const ans = [];

    // 1. 遍歷取出數字
    for (let i = 0; i < sizesLength; i++) {
        let groupSize = groupSizes[i];
        let tempGroup = [];
        // 3. 暫存尚未湊滿 groupSize 的 group
        if (tempGroups.hasOwnProperty(groupSize)) {
            tempGroup = tempGroups[groupSize];
        }
        // 2. 將 ID 放至群組
        tempGroup.push(i);
        tempGroups[groupSize] = tempGroup;
        // 4. 將群組放在最後欲回傳的變數
        if (tempGroup.length === groupSize) {
            ans.push(tempGroup);
            delete tempGroups[groupSize];
        }
    }

    return ans;
};
``` 

## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/1282.Group-the-People-Given-the-Group-Size-They-Belong-To)。
