---
title: LeetCode(240) - Search a 2D Matrix II
date: 2020-05-26 21:30:07
tags:
- leetcode
- javascript
- algorithm
- java
img: /medias/page_images/2020/leetcode-240-js.png
---
* link：[240. Search a 2D Matrix II](https://leetcode.com/problems/search-a-2d-matrix-ii/submissions/)
* difficulty：`medium`
* language：`javascript`

## 題目原文

Write an efficient algorithm that searches for a value in an m x n matrix. This matrix has the following properties:

Integers in each row are sorted in ascending from left to right.
Integers in each column are sorted in ascending from top to bottom.
Example:

Consider the following matrix:

```
[
  [1,   4,  7, 11, 15],
  [2,   5,  8, 12, 19],
  [3,   6,  9, 16, 22],
  [10, 13, 14, 17, 24],
  [18, 21, 23, 26, 30]
]
```

Given target = 5, return true.

Given target = 20, return false.


### 題目解釋

參數有兩個，一個充滿數字的二維陣列的矩陣 `matrix`，一個是要找尋的數字 `target`，用最快的方式找出 `target` 是否存在於 `matrix`。

## 解題思路

`matrix` 是一個二維陣列，有兩個規律：
* 陣列由上而下按照其值從小到大排序
* 陣列由左至右按照其值從小到大排序

這個規律帶出一個很重要的原則便是，如果當我們檢查其中一個數值的時候：
* 如果這個數值比 `target` 還小，那麼這一列左邊的所有數值都不用檢查了，因為肯定也比 `target` 小。
* 如果這個數值比 `target` 還大，那麼這一列右邊的數值也不用檢查了，因為肯定也比 `target` 大。

這是同一個的原則，逆向思考，這可以幫助我們快速篩選二維陣列的一維陣列是否要遍歷。

再來便是實際遍歷的方法，首先一樣是遍歷陣列，但由於陣列是排序過的，根據上述的原則來看，為了用最快速的方法篩選，我們可以選擇從矩陣當中的兩個方向開始遍歷，但其思路是一樣的：

### 由最右上方的數值，往左下開始遍歷

從最右上方的數值，往左下方的方向開始遍歷檢查，為什麼是右上？因為我們希望以最快的方式檢查這個陣列是否存在 `target`，而經過排序的規律可以幫助我們如此思考

* 陣列元素由上而下按照小到大排序，所以先從小的陣列開始檢查，避免 miss 掉還要遍歷回來檢查，所以是上方。
* 陣列由左而右排序，代表最右邊的一定是此列最大的數值，如果連此列最大的數值都比 `target` 小，代表此列一定不存在 `target`。

於是之後遍歷的方向就會變成，由右上往左下檢查，如果遇到比 `target` 小的數值，直接往下方移動遍歷，如果遇到比 `target` 大的數值，則往左邊遍歷。
 

### 由最左下的數值，往右上開始遍歷

這一思路跟上面是一樣的，差別只是反過來，上面是以最大的數值篩選，這裡是以用最小的數值篩選，所以從最左下角的數值開始檢查，如果數值比 `target` 還大，那麼就往上移動，繼續檢查，如果遇到數值比 `target` 小的，則往右邊檢查。


## 實作 JavaScript

將以上的思路轉為程式碼，基本上便是如此

```javascript
/**
 * @param {number[][]} matrix
 * @param {number} target
 * @return {boolean}
 */
var searchMatrix = function(matrix, target) {
    if(matrix.length === 0){
        return false;
    }
    //start with top-right element
    var i = 0;
    var j = matrix[0].length - 1;
    
    //loop till row and column number are within bounds
    while(i <= matrix.length - 1 && j >= 0){
        
        if(matrix[i][j] > target){
            //current element is greater than target
            //means this row might have the target element
            //change column
            j--;
        }else if(matrix[i][j] === target){
            //element found
            return true;
        }else if(matrix[i][j] < target){
            //current element is lesser than target
            //means this column might have the target element
            //change rows
            i++;
        }
    }
    return false;
};
```

## 實作 Java

```java
public class Solution {
    public boolean searchMatrix(int[][] matrix, int target) {
        if(matrix == null || matrix.length < 1 || matrix[0].length <1) {
            return false;
        }

        int firstIndex = 0;
        int secondIndex = matrix[0].length - 1;
        while (firstIndex < matrix.length && secondIndex >= 0) {
            if (matrix[firstIndex][secondIndex] == target) {
                return true;
            } else if (matrix[firstIndex][secondIndex] < target) {
                firstIndex++;
            } else {
                secondIndex--;
            }
        }

        return false;
    }
}
```

```java
public class HelloWorld {
    public static void main(String[] args) {
        Solution soultion = new Solution();
        int[][] matrix = {
                {1, 4, 7, 11, 15},
                {2, 5, 8, 12, 19},
                {3, 6, 9, 16, 22},
                {10, 13, 14, 17, 24},
                {18, 21, 23, 26, 30},
        };
        System.out.println(soultion.searchMatrix(matrix, 5));
        System.out.println(soultion.searchMatrix(matrix, 20));
    }
}

```

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/240.Search_a_2D_Matrix_II)。

